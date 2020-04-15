import os
import operator
from time import time

def get_files_list():
    datafiles = []
    for root, dirs, files in os.walk('./data_in_csv/'):
        for ffile in files:
            if ffile.endswith('.csv'):
                datafiles.append(ffile)
            else:
                print("WARNING:" + ffile + " is not a csv file ")
    return datafiles


def create_measures_file(files_list):

    fmeasures = open("measures.csv", "w+")

    myid = 0
    val = {}
    countries_dict = {}
    years = []

    for filename in files_list:
        category = ""
        f = open('./data_in_csv/' + filename, "r")

        yearsline = f.readline().replace("\"", "").split(";")
        category = yearsline[0].split("(")[0][:-1].replace(",", "")
        m_type = yearsline[0].split("(")[1][:-1]

        for year in [ x.strip().replace("\"", "") for x in yearsline[1:] ]:
            if year not in years:
                years.append(year)

        for line in f:
            tokens = [ x.replace("\"", "") for x in line.split(";")[:-1] ]
            if tokens[0] not in countries_dict:
                countries_dict[tokens[0]] = myid
                myid += 1
            for i in range(1, len(tokens)-1):
                fmeasures.write("\"" + str(yearsline[i]) + "\",")
                fmeasures.write("\"" + str(countries_dict[tokens[0]]) + "\",")
                fmeasures.write("\"" + category + "\",")
                val = tokens[i].replace(".", "").replace("-", "").replace(",", ".")
                if val:
                    fmeasures.write("\"" + str(float(val)) + "\",")
                else:
                    fmeasures.write("\"\\N\",")
                fmeasures.write("\"" + m_type + "\"\n")
        f.close()

    fmeasures.close()
    return countries_dict, years


def create_years_file(years):

    fyears = open("years.csv", "w+")

    years = list(set(years))
    years.sort()
    five_years_counter = 0
    five_years_start = ""
    five_years_end = ""
    for year in [ y.replace("\"", "") for y in years ]:
        if five_years_counter == 0:
            five_years_start = year
            five_years_end = int(year) + 4

        five_years_counter += 1
        fyears.write("\"" + str(year) + "\",")
        fyears.write("\"" + str(five_years_start) + "-" + str(five_years_end) + "\"\n") 

        if five_years_counter == 5:
            five_years_counter = 0

    fyears.close()


def create_countries_file(countries_dict):

    fcountries = open("countries.csv", "w+")

    sorted_dict = sorted(countries_dict.items(), key=operator.itemgetter(1))
    for key, value in sorted_dict:
        fcountries.write("\"" + str(value) + "\",\"" + key + "\"\n")

    fcountries.close()



def main():
    files_list = get_files_list()
    countries_dict, years = create_measures_file(files_list)
    create_years_file(years)
    create_countries_file(countries_dict)

t = time()
main()
print("Total time: ", (time() - t))
