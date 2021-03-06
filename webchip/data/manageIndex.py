import datetime
import json
import os
import sys
from collections import OrderedDict
from generateHeader import generate_and_add_header

class ManageIndex:
    '''This class provides an interface for modyfing index.json and the headers within data json files'''

    def __init__(self):
        # Directories to ignore
        self.ignore = set(['__pycache__', '.gitignore', 'prevFiles'])
        # Gets root of data directory
        #self.data_dir = '/'.join(os.path.realpath(__file__).split("/")[:-1]) + "/"
        self.data_dir = '/mnt/d/SSDAN/Webchip/webchip/data/'
        #self.data_dir = '/mnt/d/SSDAN/html/sites/all/themes/bootstrap_subtheme/js/data/'
        #self.data_dir = '/afs/umich.edu/group/s/ssdan/Public/html/sites/all/themes/bootstrap_subtheme/js/data/'


        self.reference_dict = self._read_file(self.data_dir + "reference.json")
        self.index_list = self._read_file(self.data_dir + "index.json")

        if not self.reference_dict:
            self.reference_dict = {}

    
    def add_file_ref(self, collection, filename, save=True):
        self.reference_dict[collection][filename] = {
            "inIndex": False,
            "lastUpdated": str(datetime.datetime.now())
        }

        if save:
            self.reference_dict[collection] = OrderedDict(sorted(self.reference_dict[collection].items()))
            return self._sort_and_update_files(sort_vals=True)

        return True

    def add_collection_ref(self, collection):
        self.reference_dict[collection] = {}

        for i in os.listdir(self.data_dir + collection):
            i = i[:-5]
            self.add_file_ref(collection, i)
        
        return self._sort_and_update_files(sort_vals=True)

    

    def get_title(self, name, collection):
        file_location = self.data_dir + collection + "/" + name
        file_data = self._read_file(file_location)

        if file_data:
            return file_data["title"]
        else:
            return False


    def edit_title(self, name, collection, new_title):
        '''
        Edits the title of a file
        Returns:
            True on sucess. False on failure
        '''

        file_location = self.data_dir + collection + "/" + name
        
        file_data = self._read_file(file_location)

        if not file_data:
            return False
        
        # Verify header and add one if necessary
        self._handle_header(collection, name, file_location)

        try:
            file_data["title"] = new_title
        except:
            print("Title was not added sucessfully to " + file_location)
            return False
        
        return self._save_generic_file(file_location, file_data)
        

    def remove_name(self, name, collection, save=True):
        '''
        Removes an entry from index.json
        Assumes if it exists it is in reference

        Input:
            name (str): The name of the file to remove
            collection (str): The collection the file is located in
        
        Returns:
            True on sucessful removal and False on failure.
        '''

        name_no_extension = name.split(".")[0]
        in_index = False
        if collection in self.reference_dict:
            if name_no_extension in self.reference_dict[collection]:
                in_index = self.reference_dict[collection][name_no_extension]["inIndex"]
        
        file_location = self.data_dir + collection + "/" + name
        self._handle_header(collection, name, file_location)

        if not in_index:
            print(collection + '/' + name + "is not in index")
            return False
        
        index = self._find_file_index(collection.lower(), name_no_extension.lower(), 0, len(self.index_list) - 1)

        self.reference_dict[collection][name_no_extension]["inIndex"] = False
        self.index_list = [self.index_list[i] for i in range(len(self.index_list)) if i != index]

        if save:
            return self._sort_and_update_files(sort_vals=False)
        
        return True

    def remove_collection(self, collection):
        '''
        Removes collection all files inside collection from index
        If collection doesn't exist nothing will happen
        '''

        for i in os.listdir(self.data_dir + collection):
            # Calls build_reference file on all json files
            if i.split(".")[-1] == "json":
                self.remove_name(i, collection, save=False)
        
        return self._sort_and_update_files()

    def insert_collection(self, collection):
        '''
        Adds collection to index along with all files inside
        If collection or files already exists it simply verifies there's a header
        '''

        for i in os.listdir(self.data_dir + collection):
            # Calls build_reference file on all json files
            if i.split(".")[-1] == "json":
                self.insert_name(i, collection, save=False)
        
        return self._sort_and_update_files()


    def insert_name(self, name, collection, save=True):
        '''
        Adds an entry to index.json
        Assumes that this entry is in the proper location but not necessarily in index or reference
        Also assumes that if something is not in reference it is not in index
        Final assumption is that new files being passed in without headers are sas formatted

        Input:
            name (str): The name of the file to remove
            collection (str): The collection the file is located in
            save (bool): True to save reference and index
        
        Returns:
            True on sucessful addition and False on failure. 
        '''

        # Check if file is in reference
        name_no_extension = name.split(".")[0]
        in_index = False
        if collection in self.reference_dict:
            if name_no_extension in self.reference_dict[collection]:
                in_index = self.reference_dict[collection][name_no_extension]["inIndex"]
        else:
            self.reference_dict[collection] = {}

        file_location = self.data_dir + collection + "/" + name
        # Verify header and add one if necessary
        self._handle_header(collection, name, file_location)

        if in_index:
            print(collection + '/' + name + " is already in index")
            return False


        # Sort reference dict changed and add to reference dict
        self.reference_dict[collection][name_no_extension] = {
            "inIndex": True,
            "lastUpdated": str(datetime.datetime.now())
        }
        self.reference_dict[collection] = OrderedDict(sorted(self.reference_dict[collection].items()))

        # Inserts new file into index
        name_cleared = name.split(".")[0]
        new_entry = {
            "path": "data/" + collection + "/" + name,
            "name": name_cleared,
            "collection": collection
        }
        self.index_list.append(new_entry)

        print(collection + '/' + name + " added to index.json")

        if save:
            return self._sort_and_update_files()

        return True
        

    def build_reference_dict(self):
        '''
        Builds a json file that has every file and says if it is in index.json
        While building  the reference if a file does not have a header one will be added
        Format:
            {
                "collection name":
                {
                    "file name": {
                        "in_index": bool,
                        "last_modified": str
                    }
                }
            }

        '''
        # I am making the assumption that the directories will not have a . in their name
        for i in os.listdir():
            # Goes through every directory withiout ignored words and with no dots, assumed to be files
            if i not in self.ignore and not '.' in i and i[0] != '.':
                self.build_reference_dir(i)
        
        self._write_to_reference()
                

    def build_reference_dir(self, collection, append=False):
        '''
        Goes through a directory and does actions described in build_reference_dict

        Input:
            name (str): The name of the file
            append (bool): If true update reference at end of function
        '''
        for i in os.listdir(self.data_dir + collection):
            # Calls build_reference file on all json files
            if i.split(".")[-1] == "json":
                self.build_reference_file(i, collection)
        
        if append:
            self._write_to_reference()

    def build_reference_file(self, name, collection, append=False):
        '''
        Goes through a file and does actions described in build_reference_dict

        Input:
            name (str): The name of the file
            collection (str): The collection the file is located in
            append (bool): If true write to reference file after function
        '''
        file_location = self.data_dir + collection + "/" + name
        file_data = self._read_file(file_location)

        if not file_data:
            return False
        
        self._handle_header(collection, name, file_location)
        
        name_no_extension = name.split(".")[0]
        found_index = self._find_file_index(collection.lower(), name_no_extension.lower(), 0, len(self.index_list) - 1)
        if found_index < 0:
            print(found_index)
        if collection not in self.reference_dict:
            self.reference_dict[collection] = {}
        
        if name_no_extension not in self.reference_dict[collection]:
            self.reference_dict[collection][name_no_extension] = {}
        
        self.reference_dict[collection][name_no_extension]["inIndex"] = (found_index > -1)
        self.reference_dict[collection][name_no_extension]["lastUpdated"] = str(datetime.datetime.now())
        print(collection + '/' + name + " was added to the reference dict")

        if append:
            self._write_to_reference()


    def _verify_header(self, name, collection):
        '''
        Verifies if a file has a header

        Input:
            name (str): The name of the file
            collection (str): The collection the file is located in 
        
        Returns:
            True if there is a header with all the proper fields and False if not
            Second boolean is whether or not it is a sas json output
        '''
        file_location = self.data_dir + collection + "/" + name
        file_data = self._read_file(file_location)

        if not file_data:
            return False, False
        
        expected_keys = ["numCats", "varCats", "title", "numOfVars", "varNames", "theData"]
        file_keys = set(file_data.keys())

        for k in expected_keys:
            if k not in file_keys:
                if 'SASJSONExport' in file_keys:
                    return False, True
                else:
                    return False, False

        return True, False
    
    def _save_current_file(self, collection, name):
        file_location = self.data_dir + collection + "/" + name
        file_data = self._read_file(file_location)
        name_no_extension = name.split(".")[0]
        current_day = str(datetime.datetime.now())
        new_file_path = self.data_dir + "prevFiles/" + name_no_extension + "_" + current_day + ".json"

        self._save_generic_file(new_file_path, file_data)

    def _handle_header(self, collection, name, file_location):
        has_header, is_sas_output = self._verify_header(name, collection)

        if not has_header:
            self._save_current_file(collection, name)
            print("Adding header to " + collection + "/" + name)
            if is_sas_output:
                generate_and_add_header(file_location, sas_file=True)
            else:
                generate_and_add_header(file_location, sas_file=False)
                
        

    def _find_file_index(self, collection, name, left, right):
        '''
        Finds if file is in index.json
        Since index is sorted we can use binary search to find items

        Input:
            collection (str): collection the in question
            name (str): name of the files
            left (int): left index of binary search
            right (int): right index of binary search
        
        Returns:
            Index of entry or -1 if it doesn't exist
        '''
        if left > right:
            return -1
        
        middle = (left + right) // 2
        middle_collection = self.index_list[middle]["collection"].lower()
        middle_name = self.index_list[middle]["name"].lower()

        if middle_collection == collection and middle_name == name:
            return middle
        elif middle_collection == collection:
            if middle_name < name:
                left = middle + 1
            else:
                right = middle - 1
        elif middle_collection < collection:
            left = middle + 1
        else:
            right = middle - 1

        return self._find_file_index(collection, name, left, right)
    
    def _read_file(self, file_path):
        '''
        Reads in file data

        Inputs:
            file_path (str): Path to the file

        Returns:
            File data if sucessful else None
        '''

        try:
            with open(file_path) as json_file: 
                return  json.load(json_file) 
        except:
            print("ERROR: " + file_path + " failed to read in")
            return False
    
    def _save_generic_file(self, file_path, new_data):
        '''
        Saves new output to file

        Inputs:
            file_path (str): Path to the file
            new_data (dict): Dictonary to save

        Returns:
            True on success False on failure
        '''
        try:
            with open(file_path, mode='w') as json_file:
                json.dump(new_data, json_file, indent=4)
                print(file_path + " successfully added")
        except:
            print("ERROR: failed to add " + file_path)
            return False
        
        return True

    def _write_to_reference(self):
        '''
        Writes the current values to reference.json
        '''

        file_path = self.data_dir + "reference.json"

        try:
            with open(file_path, mode='w') as json_file:
                json.dump(self.reference_dict, json_file, indent=4)
                print(file_path + " successfully added")
                return True
        except:
                print("ERROR: failed to add " + file_path)
                return False
    
    def _write_to_index(self):
        '''
        Writes the current values to index.json
        '''

        file_path = self.data_dir + "index.json"
        try:
            with open(file_path, mode='w') as json_file:
                json.dump(self.index_list, json_file, indent=4)
                print(file_path + " successfully added")
                return True
        except:
            print("ERROR: failed to add " + file_path)
            return False

    
    def _sort_and_update_files(self, sort_vals=True):
        '''
        Sorts and updates reference and index 
        '''
        if sort_vals:
            self.index_list = sorted(self.index_list, key=lambda x: (x["collection"], x["name"]))
            self.reference_dict = OrderedDict(sorted(self.reference_dict.items()))
        w = self._write_to_index()
        wr = self._write_to_reference()

        return w and wr
        

if __name__ == "__main__":
    num_vars = len(sys.argv)
    inputs = sys.argv
    if num_vars == 1:
        print("ERROR Inproper Input")

    editor = ManageIndex()
    if inputs[1] == "edit_title":
        print(editor.edit_title(inputs[2], inputs[3], inputs[4]))
    elif inputs[1] == "get_title":
        print(editor.get_title(inputs[2], inputs[3]))
    elif inputs[1] == "remove_name":
        print(editor.remove_name(inputs[2], inputs[3]))
    
    elif inputs[1] == "remove_collection":
        print(editor.remove_collection(inputs[2]))
    
    elif inputs[1] == "insert_collection":
        print(editor.insert_collection(inputs[2]))
    
    elif inputs[1] == "insert_name":
        print(editor.insert_name(inputs[2], inputs[3]))
    
    elif inputs[1] == "collection_ref":
        print(editor.add_collection_ref(inputs[2]))
    
    elif inputs[1] == "file_ref":
        print(editor.add_file_ref(inputs[2], inputs[3]))
    else:
        print("Incorrect input function")
        print("False")
