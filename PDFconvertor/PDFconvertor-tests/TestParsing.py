#!/usr/bin/python
# -*- coding: utf-8 -*-

#Python2.7.10

import argparse
import json

master_dict = {}  #What we are here for
pointer = 0  #Where the next line should go
answer_pointer = 0
empty_row_sequence = 0
counter_dict = {'general_info': 0, 'question': 1, 'answer': 0}

trigger_vocab = {'לפניך נתונים ל':"general_info",
				'שאלה מספר ':"question",
				'קוד מבחן':"gibberish",
				'עמוד 1 מתוך':"gibberish",
				'סוף המבחן':"gibberish",
				'משקל השאלות זהה':"gibberish",
				'‬אם ייענו יותר מ':"gibberish"}

answer_vocab = ["ה","ד","ג","ב","א"]

def get_args():
	'''This function parses and return arguments passed in'''
	# Assign description to the help doc
	parser = argparse.ArgumentParser(
		description='Script parses tests')
	# Add arguments
	parser.add_argument(
		'-i', '--input', type=str, help='Input file', required=True)
	parser.add_argument(
		'-o', '--output', type=str, help='Output file', required=True)

	# Array for all arguments passed to script
	args = parser.parse_args()
	# Assign args to variables
	input_file_name = args.input
	output_file_name = args.output
	# Return all variable values
	return input_file_name, output_file_name


def get_file_in_rows(file_name):
	'''This function opens the file, and returns the content broken into the original lines'''
	with open(file_name, "r",encoding="utf-8") as file:
		return file.read().split("\\n")


def new_field(field_type, trigger):
	'''This function adds the required field to master_dict with the fitting key
	   Possible field types are:
	   gibberish - for unrequired recognizable information
	   general_info - for general information that appears before a few connected questions
	   question - for a question
	   answer - for an answer
	'''
	global pointer
	if field_type == "gibberish":
		pointer = 0
		return
	elif field_type == "answer":
		location = field_type+str(counter_dict["question"]-1)+"_"+str(counter_dict["answer"])
	else:
		counter_dict["answer"] = 0
		location = field_type+str(counter_dict[field_type])
	master_dict[location] = [trigger]
	pointer = master_dict[location]
	counter_dict[field_type]+=1


def parse_text(text_as_rows):
	'''This is the master function, essentially parsing the text according to the trigger_vocab'''
	global pointer
	global empty_row_sequence
	for row in text_as_rows:
		if analyze(row) == "EMPTY":
			empty_row_sequence += 1
		else:
			empty_row_sequence = 0
		if empty_row_sequence == 3:
			pointer = 0

		if analyze(row) not in "EMPTY UNRECOGNIZED":
			if analyze(row) == "answer":
				new_field("answer", row[:-5])
			else:
				new_field(analyze(row), row)
		elif analyze(row) == "UNRECOGNIZED" and pointer:
			pointer.append(row)
		# if len(row) == 0:
		# 	empty_row_sequence += 1
		# else:
		# 	empty_row_sequence = 0
		# if empty_row_sequence == 3:
		# 	 pointer = 0
		# # First we gotta check for triggers from trigger_vocab:
		# for trigger in trigger_vocab:
		# 	if trigger in row:
		# 		new_field(trigger_vocab[trigger], row)
		# 		break
		# # Now let us check if it is an answer:
		# for letter in answer_vocab:
		# 	if letter in row[-12:-10]:
		# 		new_field("answer", row)
		# 		break
		# if pointer:  #If all else fails, we should continue adding to where we know
		# 	pointer.append(row)

def analyze(row):
	answer_vocab = ["ה","ד","ג","ב","א"]
	trigger_vocab = {'לפניך נתונים ל':"general_info",
					'שאלה מספר ':"question",
					'קוד מבחן':"gibberish",
					'עמוד 1 מתוך':"gibberish",
					'סוף המבחן':"gibberish",
					'משקל השאלות זהה':"gibberish",
					'‬אם ייענו יותר מ':"gibberish",
					'בכל שאלה יש לסמן':"gibberish",
					'על גבי דף התשובות':"gibberish",
					'דף התשובות מוכן לטופס':"gibberish"}

	if len(row) < 1:
		return ("EMPTY")
	for trigger in trigger_vocab:
		if trigger in row:
			return trigger_vocab[trigger]
	try:
		for letter in answer_vocab:
			if letter in row[-5] and row[-3]=='.' and row[-6]=='\u202b':  #according to input4
				return "answer"
	except IndexError:
		pass
	return "UNRECOGNIZED"

def parse_row(row, pointer):
	if len(row) == 0:
		return ("EMPTY")
	for trigger in trigger_vocab:
		if trigger in row:
			new_field(trigger_vocab[trigger], row)
			return
	for letter in answer_vocab:
		if letter in row[-12:-10]:
			new_field("answer", row)
			return
	if pointer:
		pointer.append(row)

def save_master_dict(file_name):  # TODO: not use json.dumps
	'''This functions saves the master dict to a file'''
	with open(file_name,"w") as file:
		file.write(json.dumps(master_dict))


input_file_name, output_file_name = get_args()  # Assign arguments to get in-out file names
file_in_rows = get_file_in_rows(input_file_name)  # Array of the text rows
parse_text(file_in_rows)
save_master_dict(output_file_name)  # Saves the master dict to the output file
