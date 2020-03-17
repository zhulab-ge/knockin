This repository contains a custom PHP script and workflow notes for "Robust genome editing approaches for targeted sequence insertion and replacement in rice".

Directories

    Seqfind.php	- PHP scripts to analyze the NGS reads
    probes.txt - example file of probes.txt
    sample.zip- example file of NGS, decompress before use
    Readme.txt

Note:
Add sequence to probes.txt for searching and counting, in format: Read_strand	Probe_sequence	ID DirectionOfInsert	Name	Group_ID
#Read_strand 		- string, read name and its strand direction
#Probe_sequence		- string, probe sequences for searching and counting	
#ID 			- int, ID for Probe_sequence
#DirectionOfInsert	- strand direction of your insert sequence in the probe sequences
#Name			- name of the Probe_sequence
#Group_ID		- int, from 0 to N, group id for the probe sequence

Usage:
    1. Put the three documents (Seqfind.php, probe_file, ngs_file.fq) in the same folder;
    2. Run the PHP script with this command: php -d memory_limit=-1 Seqfind.php sample.fq probes.txt
		