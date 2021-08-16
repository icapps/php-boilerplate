import xkey;
import std;

acl invalidators {
    "localhost";
}

sub vcl_recv {
    // Platform.sh specific:
    set req.backend_hint = app.backend();

    call sulu_recv;

    # Force the lookup, the backend must tell not to cache or vary on all
    # headers that are used to build the hash.
    return (hash);
}

sub vcl_backend_response {
    call sulu_backend_response;
}

sub vcl_deliver {
    call sulu_deliver;
}


/*
 * This file is part of Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

sub sulu_recv {
    if (req.method == "PURGE") {
        if (!client.ip ~ invalidators) {
            return (synth(405, "Not allowed"));
        }
        return (purge);
    }

    if (req.method == "BAN") {
        if (!client.ip ~ invalidators) {
            return (synth(405, "Not allowed"));
        }


        if (req.http.x-cache-tags) {
            ban("obj.http.x-host ~ " + req.http.x-host
                + " && obj.http.x-url ~ " + req.http.x-url
                + " && obj.http.content-type ~ " + req.http.x-content-type
                + " && obj.http.x-cache-tags ~ " + req.http.x-cache-tags
            );
        } else {
            ban("obj.http.x-host ~ " + req.http.x-host
                + " && obj.http.x-url ~ " + req.http.x-url
                + " && obj.http.content-type ~ " + req.http.x-content-type
            );
        }

        return (synth(200, "Banned"));
    }

    if (req.method != "GET" && req.method != "HEAD") {
        return (pass);
    }

    if (req.http.Authorization) {
        return (pass);
    }

    if (req.http.Cookie ~ "_ss") {
        set req.http.X-Sulu-Segment = regsub(req.http.Cookie, ".*_ss=([^;]+).*", "\1");
    }
}

sub sulu_backend_response {
    # Set ban-lurker friendly custom headers
    set beresp.http.x-url = bereq.url;
    set beresp.http.x-host = bereq.http.host;

    // Check for ESI acknowledgement and remove Surrogate-Control header
    if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
        unset beresp.http.Surrogate-Control;
        set beresp.do_esi = true;
    }

    if (beresp.http.X-Reverse-Proxy-TTL) {
        set beresp.ttl = std.duration(beresp.http.X-Reverse-Proxy-TTL + "s", 0s);
        unset beresp.http.X-Reverse-Proxy-TTL;
    }
}

sub sulu_deliver {
    if (!resp.http.X-Cache-Debug) {
        unset resp.http.x-url;
        unset resp.http.x-host;
        unset resp.http.x-cache-tags;
    }

    # Add X-Cache header if debugging is enabled
    if (resp.http.X-Cache-Debug) {
        if (obj.hits > 0) {
            set resp.http.X-Cache = "HIT";
        } else {
            set resp.http.X-Cache = "MISS";
        }
    }
}
