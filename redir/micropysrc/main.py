import network
import time
import urequests
import ujson

def find_proxy(base: str, port: int, start: int, end: int, token: str):
    for host in range(start, end+1):
        request = "http://%(b)s.%(h)d:%(p)d/features" % {'b': base, 'h': host, 'p': port}
        try:
            response = urequests.request("GET", request, headers=headers)
            if response.status_code == 200:
                js = ujson.loads(str(response.text))
                try:
                    data = js['features']
                    if "redir" in data:
                        return "http://%(b)s.%(h)d:5000" % {'b': base, 'h': host}
                except KeyError:
                    pass
        except OSError:
            pass
    return None


while True:
    try:
        fh = open("/conf.json")
        content = fh.read()
        fh.close()
        js = ujson.loads(content)
        proxy_token = js["proxy_token"]
        proxy_port = js["proxy_port"]
        dest_token = js["dest_token"]
        dest_url = js["dest_url"]
        wifi_ssid = js["ssid"]
        wifi_password = js["password"]
        hostname = js["hostname"]
        station = network.WLAN(network.STA_IF)
        station.active(True)
        station.connect(wifi_ssid, wifi_password)
        time.sleep(30)
        ip_addr = station.ifconfig()[0]
        headers = {"Content-type": "application/json",
                   "Authorization": f"Bearer {proxy_token}",
                   "Accept": "application/json"}
        proxy = find_proxy("192.168.55", int(proxy_port), 101, 150, proxy_token)
        if proxy:
            # IP reporting
            data = {"redir": {"target-host": dest_url,
                              "target-token": dest_token},
                    "hostname": hostname,
                    "ip": ip_addr}
            request = "%(p)s/adres" % {'p': proxy}
            response = urequests.request("PUT", request, headers=headers, json=data)
            if response.status_code == 201:
                js = ujson.loads(str(response.text))
                print(js)
            else:
                print(response.status_code)
                print(response.text)
            station.disconnect()
        else:
            print("Proxy not found")
    except Exception as e:
        print(str(e))
    time.sleep(3600)
