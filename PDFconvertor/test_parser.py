#!/usr/bin/env python
# -*- coding: utf-8 -*-

import json
import codecs

test_file = open ("output.txt","r")
file_rows = test_file.read().split("\\n")
test_file.close()

master_dict = {}  #What we are here for
pointer = 0  #Where the next line should go
counter_dict = {'general_info':0, 'question':0}

def new_general_info(trigger):
	global pointer
	master_dict ['general_info'+str(counter_dict['general_info'])] = [trigger]
	pointer = master_dict ['general_info'+str(counter_dict['general_info'])]
	counter_dict['general_info']+=1

def new_question(trigger):
	global pointer
	master_dict ['question'+str(counter_dict['question'])] = [trigger]
	pointer = master_dict ['question'+str(counter_dict['question'])]
	counter_dict['question']+=1

def gibberish(trigger):
	global pointer
	pointer = 0

action_vocab = ['לפניך נתונים ל']
action_vocab = {'לפניך נתונים ל':new_general_info,
				'שאלה מספר ':new_question,
				'קוד מבחן':gibberish,
				'עמוד 1 מתוך':gibberish,
				'עמוד 2 מתוך':gibberish,
				'עמוד 3 מתוך':gibberish,
				'עמוד 4 מתוך':gibberish,
				'עמוד 5 מתוך':gibberish,
				'עמוד 6 מתוך':gibberish,
				'עמוד 7 מתוך':gibberish,
				'עמוד 8 מתוך':gibberish,
				'עמוד 9 מתוך':gibberish,
				'עמוד 10 מתוך':gibberish,
				'עמוד 11 מתוך':gibberish,
				'סוף המבחן':gibberish}

for row in file_rows:
	for trigger in action_vocab:  #Check if there is something we know about the line
		if trigger in row:
			action_vocab[trigger](row)
			break
	else:
		if pointer != 0:  #If we know where we need to add
			pointer.append(row)


def printer(master_dict):
	for k in master_dict:
		print (k)
		for v in master_dict[k]:
			print (v)

printer(master_dict)
data = json.dumps(master_dict)
data=data.encode('latin1')
data=data.decode('windows-1255')
parsed_file = open ('parsed_test4.json','w+',encoding="utf-8")
parsed_file.write(data)
parsed_file.close()
