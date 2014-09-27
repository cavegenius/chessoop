var isNN = (navigator.appName.indexOf("Netscape")!=-1);

function autoTab(input,len, e) {
  var keyCode = (isNN) ? e.which : e.keyCode; 
  var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
  if(input.value.length >= len && !containsElement(filter,keyCode)) {
    input.value = input.value.slice(0, len);
    input.form[(getIndex(input)+1) % input.form.length].focus();
  }

  function containsElement(arr, ele) {
    var found = false, index = 0;
    while(!found && index < arr.length)
    if(arr[index] == ele)
    found = true;
    else
    index++;
    return found;
  }

  function getIndex(input) {
    var index = -1, i = 0, found = false;
    while (i < input.form.length && index == -1)
    if (input.form[i] == input)index = i;
    else i++;
    return index;
  }
  return true;
}

function moveSelection(selection) { // create the function using a submitted value of the cells ID
	if (selection == document.moveForm.from.value) { // checking if they clicked on the same cell a second time so we can deselect the cell
		document.moveForm.from.value = ""; // unset the value of the from field in the form
		document.getElementById(selection).style.backgroundColor = previousColor; // set the color of the cell back to what it should be
	} else if (document.moveForm.from.value == "") { // if this is the first click the from field is empty
		document.moveForm.from.value = selection; // set the value of the from field to the ID of the cell
		previousColor = document.getElementById(selection).style.backgroundColor; // set the previous color incase they deselect the cell
		document.getElementById(selection).style.backgroundColor = "#ffffff"; // set the background color to white
	} else { // if its the second click and is not same cell as first
		document.moveForm.to.value = selection; // set the value of the to field. 
		document.getElementById(selection).style.backgroundColor = "#ffffff"; // change background
		document.moveForm.submit(); // submit the form
	}
}