#!/usr/bin/env python3

import mysql.connector


class Db:
    def __init__(self):
        self.connection = None
        self.cursor = None

    def connect(self, host: str, user: str, password: str, database: str):
        try:
            self.connection = mysql.connector.connect(host=host,
                                                      user=user,
                                                      password=password,
                                                      database=database)
            self.cursor = self.connection.cursor()
        except mysql.connector.errors.ProgrammingError:
            pass

    def query(self, query: str):
        self.cursor.execute(query)
        result = None
        try:
            result = self.cursor.fetchall()
        except mysql.connector.errors.InterfaceError:
            pass            
        self.connection.commit()
        return result
