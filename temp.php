    <!-- Button trigger modal -->
    <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>
        Create an activity
    </button>

    <!-- Modal -->
    <div class='modal fade' id='staticBackdrop' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1'
        aria-labelledby='staticBackdropLabel' aria-hidden='true'>
        <div class='modal-dialog' action='userAll.php' method='POST'>
            <form class='modal-content' method='POST' action='userAll.php'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='staticBackdropLabel'>Create activity</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <div class='mb-3'>
                        <label for='exampleFormControlInput1' class='form-label md'>Title</label>
                        <input name='title' type='text' class='form-control' id='exampleFormControlInput1'
                            placeholder='Title here...'>
                    </div>
                    <div class='form-floating'>
                        <textarea name='content' class='form-control' placeholder='Leave a comment here'
                            id='floatingTextarea2' style='height: 100px'></textarea>
                        <label for='floatingTextarea2'>Content</label>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                    <button name='create' type='submit' class='btn btn-primary' id='createB'>Send</button>
                </div>
            </form>
        </div>
    </div>