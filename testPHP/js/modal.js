function editTitleClick(file, collection){
    //Variables
    //First add a function to get the title of a file
    // Name of File
    // Name of collection
    // The current title name
    let modal = document.getElementById("myModal");
    let content = document.getElementById("hiddenContentDiv");

    // In order to get the title, I will have to make get request to the server in order to get the value
    // This is the endpoint for the api http://localhost:8000/api/title.php?collection=abc123&file=employ10.json
    // It is returned as an array where first value is the name of the title

    let url = `http://localhost:8000/scripts/title.php?collection=${collection}&file=${file}`;
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", url, false ); // false for synchronous request
    xmlHttp.send( null );
    
    let currentTitle = JSON.parse(xmlHttp.responseText);
    
    console.log(content.innerHTML);
    let formVals = `<form method="post" action="">
     <input type="text" name="title" value=${currentTitle}>
     <input type="hidden" name="collection" value=${collection} >
     <input type="hidden" name="file" value=${file}>
     <br>
    <input type="submit" value="Edit">
    </form>`
    content.innerHTML = `${formVals}`;

    if(modal.style.display !== "block"){
        modal.style.display = "block";
    }
}
 /*
function editIndex(file, collection, funcType){
    let url = `http://localhost:8000/api/edit.php?collection=${collection}&file=${file}&funcType=${funcType}`;
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", url, false ); // false for synchronous request
    xmlHttp.send( null );

    let success = JSON.parse(xmlHttp.responseText);

    if(file != "none"){
        if(success){
            window.location=`http://localhost:8000/files.php?collection=${collection}&updated=1&file=${file}`;
        }
    
        else{
            window.location=`http://localhost:8000/files.php?collection=${collection}&updated=0&file=${file}`;
        }
    }

    else{
        if(success){
            window.location=`http://localhost:8000/index.php?collection=${collection}&updated=1`;
        }
    
        else{
            window.location=`http://localhost:8000/files.php?collection=${collection}&updated=0`;
        } 
    }
    
}
*/

function allClicks(e){
    let modal = document.getElementById("myModal");
    if (e.target == modal) {
        modal.style.display = "none";
      }
}


function closeModal(){
    let modal = document.getElementById("myModal");
    modal.style.display = "none";
}

window.addEventListener("click", allClicks);


//Updating works now
// Add button for adding or removing specific file

// First: Change index to new index and make sure ref is okay
// Next: Test using the api for editing stuff without UI.
// As long as that works try https for the website instead. That may fix it.?
// Locally it seemed to have similar effect when I changed 8000 to 8001