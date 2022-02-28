// Variables
const states = [
    "Alaska",
    "Alabama",
    "Arkansas",
    "American Samoa",
    "Arizona",
    "California",
    "Colorado",
    "Connecticut",
    "District of Columbia",
    "Delaware",
    "Florida",
    "Georgia",
    "Guam",
    "Hawaii",
    "Iowa",
    "Idaho",
    "Illinois",
    "Indiana",
    "Kansas",
    "Kentucky",
    "Louisiana",
    "Massachusetts",
    "Maryland",
    "Maine",
    "Michigan",
    "Minnesota",
    "Missouri",
    "Mississippi",
    "Montana",
    "North Carolina",
    "North Dakota",
    "Nebraska",
    "New Hampshire",
    "New Jersey",
    "New Mexico",
    "Nevada",
    "New York",
    "Ohio",
    "Oklahoma",
    "Oregon",
    "Pennsylvania",
    "Puerto Rico",
    "Rhode Island",
    "South Carolina",
    "South Dakota",
    "Tennessee",
    "Texas",
    "Utah",
    "Virginia",
    "Virgin Islands",
    "Vermont",
    "Washington",
    "Wisconsin",
    "West Virginia",
    "Wyoming",
    "Andaman and Nicobar (UT)",
    "Andhra Pradesh",	
    "Arunachal Pradesh",
    "Assam",						
    "Bihar",			
    "Chandigarh (UT)",
    "Chhattisgarh",
    "Dadra and Nagar Haveli (UT)",
    "Daman and Diu (UT)",
    "Delhi",		
    "Goa",					
    "Gujarat",
    "Haryana",			
    "Himachal Pradesh",
    "Jammu and Kashmir",
    "Jharkhand",			
    "Karnataka",					
    "Kerala",			
    "Lakshadweep (UT)",
    "Madhya Pradesh",			
    "Maharashtra",				
    "Manipur",				
    "Meghalaya",					
    "Mizoram",					
    "Nagaland",					
    "Orissa",			
    "Puducherry (UT)",
    "Punjab",					
    "Rajasthan",					
    "Sikkim",				
    "Tamil Nadu",					
    "Telangana",					
    "Tripura",				
    "Uttar Pradesh",
    "Uttarakhand",				
    "West Bengal"
];
let autocomplete = null;
let autocompleteService;
let predictions = null;
let choices = null;
let fileCategories = null;
let uppies = [];
let whichUppyIsRequired = [];
let redirectEnabled = true;
let fileCount = 0;
let filesUploaded = 0;

// Map stuff
let service;
let map;
let marker;

const toSnakeCase = str => str && str.match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g).map(x => x.toLowerCase()).join('_');

document.addEventListener("DOMContentLoaded", function () {
    loadStateOptions();
    M.Carousel.init(document.querySelectorAll('.carousel'));

    // Initialize address auto-select
    let elem = document.getElementById("autocomplete-input");
    M.Autocomplete.init(elem, {
        onAutocomplete: addressSelected
    });
    autocomplete = M.Autocomplete.getInstance(elem);
    autocomplete._handleInputKeydown = function () {
    };
    // Map stuff
    autocompleteService = new google.maps.places.AutocompleteService();

    elem.addEventListener("keyup", debounce(keyup, 100));

//    init Uppy instances only if project is not archived
    if (projectStatus !== 'archived') {
        fileCategories = JSON.parse(fileTypes);
        fileCategories.forEach((cat, index) => {
            uppies.push(Uppy.Core({
                id: cat.name,
                debug: true,
                meta: {
                    file_type_id: cat.id,
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#${toSnakeCase(cat.name)}`,
                inline: true,
                hideUploadButton: hideUploadButton === 'yes',
                note: "Upto 20 files of 20 MBs each"
            }).use(Uppy.XHRUpload, {
                id: cat.name,
                endpoint: sunStorage + '/file',
                headers: {
                    'api-key': sunStorageKey
                },
                fieldName: "file"
            }));
            uppies[index].on('upload-success', sendFileToDb);
            uppies[index].on('file-added', (file) => {
                fileCount++;
            });

            uppies[index].on('file-removed', (file) => {
                fileCount--;
            });

            if (projectIdOverload) {
                uppies[index].setMeta({project_id: projectIdOverload, path: `genesis/${company}/projects/${projectIdOverload}/${uppies[index].getID()}`})
            }
            whichUppyIsRequired.push(cat.pivot.is_required);
        });
    }else
        document.getElementById('uppies').style.display = 'none';


//    disable inputs if project is being viewed
    if (projectIdOverload) {
        // do not redirect on file upload
        redirectEnabled = false;

        let inputs = document.forms['lead_form'].getElementsByTagName('input');
        for (const input of inputs) {
            input.disabled = true;
        }
        document.forms['lead_form'].getElementsByTagName('textarea')[0].disabled = true;
    }

    // Initialize map
    map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: (latOverload) ? parseFloat(latOverload) : 18.9255728,
            lng: (longOverload) ? parseFloat(longOverload) : 72.8242221
        },
        zoom: 16,
        mapTypeId: 'satellite'
    });
    service = new google.maps.places.PlacesService(map);
    map.addListener("click", mapClickListener);

    if (latOverload && longOverload) {
        marker = new google.maps.Marker({
            map: map,
            position: map.center
        });
        map.panTo(marker.getPosition());
    }
});

// Event listener for the address fields
let addressinputs = document.getElementsByName("address");

for (let index = 0; index < addressinputs.length; index++) {
    addressinputs[index].addEventListener('focusout', function (event) {

        if (event.currentTarget.value !== "") {
            event.preventDefault();

            let enteredaddress = "";
            addressinputs.forEach(element => {
                enteredaddress += element.value + " ,";
            });
            refreshMap(enteredaddress)
        }
    })
}

// populate state select
function loadStateOptions() {
    const st = document.querySelector("#state");
    st.innerHTML = "";
    st.add(new Option("Select State", "", true));
    states.forEach(function (item) {
        st.add(new Option(item, item, false, stateOverload === item));
    });
    M.FormSelect.init(st);
}

// Make dropdown list for address auto complete
function makeSuggestions(googleData) {
    let data = {};
    predictions = {};
    googleData.forEach(item => {
        //remove special chars
        let string = item.description.replace(/[^\w\s]/gi, "");
        data[string] = null;
        predictions[string] = {
            words: item.terms,
            id: item.place_id
        };
    });
    return data;
}

// Debounce for autocomplete
function debounce(func, wait, immediate) {
    let timeout;

    return function () {
        let context = this,
            args = arguments;

        // remove specials
        this.value = this.value.replace(/[^\w\s]/gi, "");

        // remove extra spaces
        this.value = this.value.replace(/ +(?= )/g, "");

        let callNow = immediate && !timeout;

        clearTimeout(timeout);

        timeout = setTimeout(function () {
            timeout = null;
            if (!immediate) {
                func.apply(context, args);
            }
        }, wait);

        if (callNow) func.apply(context, args);
    };
}

// Make new marker in when clicked on map
mapClickListener = function (event) {
    if (marker) marker.setMap(null);

    marker = new google.maps.Marker({
        map: map,
        position: event.latLng
    });
    map.panTo(marker.getPosition());
    getAddress(event.latLng);
    document.querySelector("#lat").value = marker.getPosition().lat();

    document.querySelector("#long").value = marker.getPosition().lng();
    M.updateTextFields();
};

function getAddress(_placeid) {
    try {


        if (_placeid) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'latLng': _placeid
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        bindAddress(results[0]);
                    } else
                        throw new Error('No places address returned.');
                } else
                    throw new Error('GMaps api error');
            });
        } else
            throw new Error("No lat and long details returned from the map selection");
    } catch (error) {
        console.log("Error when getting place details: " & error.message);
        M.toast({
            html: "Not a valid place. Cannot determine the selected place address.Please check the address before you save",
            classes: "red-back"
        });
    }
}

//Refresh in map on address entry
function refreshMap(_address) {
    // Search for Google's office in Australia.
    var request = {
        query: _address
    };

    service.textSearch(request, function (place, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            if (marker) marker.setMap(null);

            marker = new google.maps.Marker({
                map: map,
                position: place[0].geometry.location
            });
            map.panTo(marker.getPosition());
            addressSelected(place[0].place_id, true);
            M.updateTextFields();
        }

    });

}

// Address predict selection
addressSelected = function (selection, placeID = false) {
    let _placeid, request;

    if (placeID)
        _placeid = selection;
    else
        _placeid = predictions[selection].id;

    request = {
        placeId: _placeid,
        fields: [
            "name",
            "formatted_address",
            "address_components",
            "place_id",
            "geometry"
        ]
    };


    service.getDetails(request, function (place, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            if (marker) marker.setMap(null);

            marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location
            });
            map.panTo(marker.getPosition());

            document.querySelector(
                "#lat"
            ).value = place.geometry.location.lat();
            document.querySelector(
                "#long"
            ).value = place.geometry.location.lng();
            bindAddress(place);
            /*
                                let streetStr = "";

                                place.address_components.forEach(item => {
                                    if (
                                        item.types[0] === "point_of_interest" ||
                                        item.types[0] === "establishment" ||
                                        item.types[0] === "street_number" ||
                                        item.types[0] === "route"
                                    )
                                        streetStr += " " + item.short_name;
                                    else if (item.types[0] === "locality") {
                                        document.getElementById("city").value = item.short_name;
                                    } else if (item.types[0] === "administrative_area_level_1") {
                                        let stateSelect = document.getElementById("state");
                                        stateSelect.value = item.long_name;
                                        M.FormSelect.init(stateSelect);
                                    } else if (item.types[0] === "postal_code") {
                                        document.getElementById("zip").value = item.short_name;
                                    }
                                });

                                document.getElementById("street").value = streetStr; */
            M.updateTextFields();
        }
    });
};

// keyup event to debounce for autocomplete
keyup = function (elem) {
    if (elem.target.value) {
        autocompleteService.getQueryPredictions({
                input: elem.target.value
            },
            displaySuggestions
        );
    }
};

// function to fire when typing in autocomplete
let displaySuggestions = function (predictions, status) {
    if (status !== google.maps.places.PlacesServiceStatus.OK) {
        console.log(status);
        M.toast({
            html: status,
            classes: "red-back"
        });
        return;
    }
    autocomplete.updateData(makeSuggestions(predictions));

    autocomplete.close();
    autocomplete.open();
};

function bindAddress(_place) {
    let streetStr = "";

    _place.address_components.forEach(item => {
        if (
            item.types[0] === "point_of_interest" ||
            item.types[0] === "establishment" ||
            item.types[0] === "street_number" ||
            item.types[0] === "route"
        )
            streetStr += " " + item.short_name;
        else if (item.types[0] === "locality") {
            document.getElementById("city").value = item.short_name;
        } else if (item.types[0] === "administrative_area_level_1") {
            let stateSelect = document.getElementById("state");
            stateSelect.value = item.long_name;
            M.FormSelect.init(stateSelect);
        } else if (item.types[0] === "postal_code") {
            document.getElementById("zip").value = item.short_name;
            // console.log(item.short_name);
        } else if (item.types[0] === "country") {
            document.getElementById("country").value = item.long_name;
            // console.log(item.long_name);
        }
    });

    document.getElementById("street").value = streetStr;
}

// validate for inputs
function validateFields(skipUppies = false) {
    let form = document.forms["lead_form"].getElementsByTagName("input");
    let errors = 0;
    let jsonData = {};

    //Make the thing green
    function right(item) {
        item.classList.remove("invalid");
        item.classList.add("valid");

        if (item.getAttribute("name"))
            jsonData[item.getAttribute("name")] = item.value;
    }

    //Make the thing red
    function wrong(item) {
        item.classList.remove("valid");
        item.classList.add("invalid");
        errors++;
    }

    // Strip all the FUNKY chars from the string
    function stripSpecials(string) {
        //remove special chars
        string = string.replace(/[^\w\s.]/gi, "");
        //remove spaces
        string = string.replace(/\s+/g, "").trim();
        return string.toLowerCase();
    }

    // All the non-select inputs
    for (let item of form) {
        if (item.getAttribute("validate") === "string") {
            if (!validate.single(item.value, {presence: {allowEmpty: false}}))
                right(item);
            else
                wrong(item);
        } else if (item.getAttribute("validate") === "email") {
            if (!validate.single(item.value, {
                presence: {allowEmpty: false},
                format: '^(([^<>()\\[\\]\\\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$'
            }))
                right(item);
            else
                wrong(item);
        } else if (item.getAttribute("validate") === "phone") {
            if (!validate.single(stripSpecials(item.value), {presence: {allowEmpty: false}, numericality: true, length: {is: 10}})
            )
                right(item);
            else wrong(item);
        } else if (item.getAttribute("validate") === "postcode") {
            if (!validate.single(stripSpecials(item.value), {presence: {allowEmpty: false}, numericality: true}))
                right(item);
            else
                wrong(item);
        } else if (item.getAttribute("validate") === "lat_long") {
            if (!validate.single(stripSpecials(item.value), {presence: {allowEmpty: false}, numericality: true}))
                right(item);
            else wrong(item);
        } else if (item.getAttribute("validate") === "letters") {
            if (!validate.single(item.value, {presence: {allowEmpty: false}, format: "^[a-zA-Z ]+$"}))
                right(item);
            else
                wrong(item);
        }
    }

    const state = M.FormSelect.getInstance(document.querySelector("#state"));
    M.FormSelect.init(state);

    if (state.getSelectedValues()[0] === "") wrong(state.wrapper);
    else {
        right(state.wrapper);
        jsonData["state"] = state.getSelectedValues()[0];
    }

    const description = document.getElementById('description');
    if (description.value === "") wrong(description);
    else {
        right(description);
    }

    // validate uppies
    if (!skipUppies)
        whichUppyIsRequired.forEach((is_required, index) => {
            if (is_required) {
                if (uppies[index].getFiles().length === 0) {
                    errors++;
                    document.getElementById(uppies[index].getID() + "_error").innerText = "Please attach at least one file";
                } else
                    document.getElementById(uppies[index].getID() + "_error").innerText = "";
            }
        });

    return {
        errors: errors,
        columns: jsonData
    };
}

// insert files and project
function insert() {
    const validationResult = validateFields();

    function uploadFiles(project_id) {
        uppies.forEach(uppy => {
            uppy.setMeta({project_id: project_id, path: `genesis/${company}/projects/${project_id}/${uppy.getID()}`})
            uppy.upload();
        })
    }

    if (validationResult.errors === 0) {
        axios(post, {
            method: 'post',
            data: validationResult.columns,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
            }
        }).then(response => {
            if (response.status === 200 || response.status === 201) {
                console.log(response)
                uploadFiles(response.data.id);
            } else {
                toastr.error('There was a error inserting the project. Please try again.', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                console.error(response);
            }
        }).catch(err => {
            toastr.error('There was a network error. Please try again.', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
            console.error(err);
        });
    } else {
        toastr.error('There are some errors in your form, please fix them and try again.', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
    }
}

function update() {
    const validationResult = validateFields(true);
       
    if (validationResult.errors === 0) {
        axios(postUpdate, {
            method: 'post',
            data: validationResult.columns,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
            }
        }).then(response => {
            if (response.status === 200 || response.status === 201) {
                console.log(response.data)
                toastr.success('Project Information updated!.', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
            } else {
                toastr.error('There was a error updating the project. Please try again.', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                console.error(response);
            }
        }).catch(err => {
            toastr.error('There was a network error. Please try again.', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
            console.error(err);
        });
    } else {
        toastr.error('There are some errors, in your form, Pleas fix them and try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
    }
}

const sendFileToDb = function (file, response) {

    axios(fileInsert, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
        },
        data: {
            path: response.body.name,
            file_type_id: file.meta.file_type_id,
            project_id: file.meta.project_id,
            content_type: file.meta.type
        }
    }).then(response => {
        if (response.status === 200 || response.status === 201) {
            console.log(response.data);
            toastr.success('Files Uploaded!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
        } else {
            toastr.error('There was a error uploading images. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
            console.error(response);
        }
        filesUploaded++;
        if ((filesUploaded === fileCount) && redirectEnabled)
            window.location = redirect;
    }).catch(err => {
        toastr.error('There was a network error uploading images. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
        console.error(err);
    });

};
