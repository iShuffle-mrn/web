#!/bin/bash
node test3-worked.js>Output.txt
python3 TestParsing.py -i Output.txt -o output2-json.json
