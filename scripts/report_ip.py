#!/usr/bin/python3

import fcntl
import socket
import struct
import requests
import sys
import os

TOKEN = 'S29uc3RhbnR5bm9wb2xpdGFuY3p5a2lld2ljem93bmE='

def __usage__(msg=None):
    if msg:
        print(msg)
        print()
    print("Usage:")
    print()
    print(sys.argv[0] + " <interface> <url>")
    print()

if __name__ == "__main__":
    if len(sys.argv) < 3:
        __usage__("Missing param(s)")
        sys.exit(1)
    token = os.getenv("TOKEN")
    if not token:
        __usage__("Missing env variable TOKEN")
        sys.exit(1)
    interface = sys.argv[1]
    sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    pack = struct.pack('256s', interface.encode('utf_8'))
    addr = fcntl.ioctl(sock.fileno(), 0x8915, pack)[20:24]
    ip_addr = socket.inet_ntoa(addr)
    hostname = socket.gethostname()
    data = {"hostname": hostname, "ip": ip_addr}
    headers = {"Username": "ReportIP", "Authorization": f"Bearer {token}"}
    response = requests.put(sys.argv[2], json=data, headers=headers)
    print(f"Reported: {hostname} = {ip_addr}")
    print(f"Received: {response.text}")
