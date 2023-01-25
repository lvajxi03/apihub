#!/usr/bin/env python3

import configparser

def get(filename):
    filename = filename.strip()
    if filename:
            config = configparser.ConfigParser()
            config.read(filename)
            return config
    return None
