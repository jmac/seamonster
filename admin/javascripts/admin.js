// DRAGGGABLE JUNK

document.observe("dom:loaded", function() { 
  Sortable.create('attached-files',  {elements:$$('#attached-files p'), handles:$$('#attached-files label')}); 
});


var ResizingTextArea = Class.create();

ResizingTextArea.prototype = {
  defaultRows: 1,

  initialize: function(field)
  {
    this.defaultRows = Math.max(field.rows, 1);
    this.resizeNeeded = this.resizeNeeded.bindAsEventListener(this);
    Event.observe(field, "click", this.resizeNeeded);
    Event.observe(field, "keyup", this.resizeNeeded);
  },

  resizeNeeded: function(event)
  {
    var t = Event.element(event);
    var lines = t.value.split('\n');
    var newRows = lines.length + 1;
    var oldRows = t.rows;
    for (var i = 0; i < lines.length; i++)
    {
        var line = lines[i];
        if (line.length >= t.cols) newRows += Math.floor(line.length / t.cols);
    }
    if (newRows > t.rows) t.rows = newRows;
    if (newRows < t.rows) t.rows = Math.max(this.defaultRows, newRows);
  }
}

function removeFile(element) {
  
  var check = confirm("Are you sure you want to unattach this file from this page? (Don't worry, this won't delete the file from the server)");
  
  if (check == true) {
    $(element).remove();
  }
  
}

function addFile(element) {
  
  attachedFileCount += 1;
  pageObjectCount += 1;
  
  var title = prompt("Please enter a title for the attached file:", "");
  
  if (title){
    var newlyAttachedFile = new Element('p', { id: ('item-' + pageObjectCount), 'class': 'existingfile draggable' }).update("<label><span>" + title + "</span><input class=\'input-disabled\' type=\'text\' disabled=\'disabled\' name=\'display[]\' value=\'" + element + "\' /> <input type=\'hidden\' name=\'existing-file[" + attachedFileCount + "][Name]\' value=\'" + element + "\' /> <input type=\'hidden\' name=\'existing-file[" + attachedFileCount + "][Caption]\' value=\'" + title + "\' /> <a href=\"#\" onclick=\"removeFile($(\'item-" + pageObjectCount + "\')); return false;\" class=\'delete\'>&nbsp;</a></label>");
    
    $('attached-files').insert ({
      'bottom' : newlyAttachedFile
    } );
  
    Sortable.create('attached-files',  {elements:$$('#attached-files p'), handles:$$('#attached-files label')});
  } else {
    alert("You need to specify a title for the file - please try again.")
  }
}