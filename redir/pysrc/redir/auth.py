#!/usr/bin/env python3

import base64

def auth(db, token: str) -> bool:
    if token:
        token = base64.b64decode(token).decode("utf-8")
    if db:
        query = "SELECT data, token FROM auth_tokens"
        result = db.query(query)
        if len(result) == 1:
            data, tok = result[0]
            if tok == token:
                return True
    return False
