function onAlbumChange()
{
    var albumChanged = document.getElementById('albumChangedFlag');
    albumChanged.value = "1";
    
    var albumSelectionForm = document.getElementById('picture-form');
    albumSelectionForm.submit();
}