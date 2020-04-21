# data-visualization-assignment

## Εφαρμογή Οπτικοποίησης δεδομένων

Διεπαφή σε HTML, με την οποία γίνεται αναζήτηση σε ένα σύνολο δεδομένων από την ιστοσελίδα GapMinder, και στη συνέχεια οπτικοποίηση (D3.js) των αποτελεσμάτων για την εξαγωγή συμπερασμάτων.

### /files
Εκφώνηση: files/Project-2018-2019.pdf
Πλήρης περιγραφή: files/Report.pdf

### /ETL
Χρησιμοποιήθηκαν τα scripts: ETL/create_files.py, ETL/fill_matrices.py

### /application
Η εφαρμογή, με αρχική σελίδα την application/index.html

### Installation
1. Install XAMPP.
2. Move files/Dump20190529.sql to xampp folder. (c:\\xampp)
3. Start MySQL and in shell type the following:  
```
# mysql -u root -p
(hit 'Enter' if no password added)
> create database mydb;
> exit
# mysql -u root -p mydb < Dump20190529.sql
```
4. Move application/myFiles to xampp/htdocs. (c:\\xampp\\htdocs)
5. Open your browser and browse localhost/myFiles.

### Tested on
Windows 10
XAMPP 7.4.4
