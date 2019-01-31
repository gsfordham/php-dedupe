# php-dedupe
PHP script to remove duplicate files in a directory

Quick and simple PHP script to dedupe files. This will run a SHA-512 hash on each file in the directory and build an indexed file array for them.

After selecting "y", that you wish to continue, it will create a directory for duplicates and store every file after the first occurrence in that directory to be deleted.

*To do:*
1) Make an option to automatically delete all duplicates, rather than storing for manual deletion in the new subdirectory.
