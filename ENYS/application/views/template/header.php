<html>
    <head>
        <title>ENYS</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro|Open+Sans+Condensed:300|Raleway' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css" type="text/css">
        <script src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>

    <body>

        <script>
            // Show select image using file input.
            function readURL(input) {
                $('#default_img').show();
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#select')
                            .attr('src', e.target.result)
                            .width(300)
                            .height(200);

                    };

                    reader.readAsDataURL(input. files[0]);
                }
            }
        </script>

        <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
            <a class="navbar-brand font-weight-bolder" href="#" style="font-family: 'Pacifico', cursive;">ENYS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo base_url(); ?>">
                            <i class="fas fa-home"></i> Home 
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>discussion">
                            <i class="fas fa-list-alt"></i> Discussion Boards
                        </a>
                    </li>

                    <!--
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-list-alt"></i> Dropdown
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                     -->

                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-heart"></i> My Favorites
                        </a>
                    </li>
                    -->

                    <?php if(!$this->session->userdata('logged_in')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>login"> 
                            <i class="fas fa-sign-in-alt"></i> Login 
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if($this->session->userdata('logged_in')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>upload">
                            <i class="fas fa-upload"></i> Upload
                        </a>
                    </li>

                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>manipulation">
                            <i class="fas fa-upload"></i> Manipulation
                        </a>
                    </li>
                    

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>email"> 
                            <i class="fas fa-mail-bulk"></i> Email
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>profile"> 
                            <i class="fas fa-user"></i> My Profile
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>login/logout"> 
                            <i class="fas fa-sign-out-alt"></i> Logout 
                        </a>
                    </li>

                    <?php endif; ?>

                </ul>

                <!--
                <form class="form-inline my-2 my-lg-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    </div>
                    <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
                </form>
                -->
                <form class="form-inline my-2 my-lg-0">
                    <?php echo form_open('ajax'); ?>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input class="form-control mr-sm-2" type="search" id="search_text" name="search" placeholder="Search" aria-label="Search">
                    </div>
                    <button class="btn btn-outline-secondary search my-2 my-sm-0" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></button>
                    <?php echo form_close(); ?>
                </form>

                <!--
                <form class="form-inline my-2 my-lg-0">
                
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input class="form-control mr-sm-2" type="search" id="search_text" name="search" placeholder="Search" aria-label="Search">
                    </div>
                    <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Search</button>
                    
                </form>
                -->

            </div>
        </nav>

        <div class="container py-5">

            <div class="collapse my-5" id="collapseExample">
                <div class="card card-body" id="result">
                    <h1>Result</h1>       
		            <p>Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.</p>
                    <br>
                </div>
            </div>

            <script>
                $(document).ready(function(){
                    load_data();
                    function load_data(query){
                        $.ajax({
                            url:"<?php echo base_url(); ?>ajax/fatch",
                            method:"GET",
                            data:{query:query},
                            success:function(response){
                                $('#result').html("");
                                if (response == "" ) {
                                    $('#result').html(response);
                                } else {
                                    var obj = JSON.parse(response);
                                    if (obj.length>0) {
                                        var items=[];
                                        $.each(obj, function(i,val){
                                            items.push($("<h4>").text(val.filename));
                                            if (val.filename.includes("jpg")) {
                                                items.push($('<img width="320" height="240" src="' +'<?php echo base_url(); ?>/uploads/' +val.filename + '" />'));
                                            } else {
                                                items.push($('<video width="320" height="240" controls><source  src="' +'<?php echo base_url(); ?>/uploads/' +val.filename + '" type="video/mp4"></video>'));
                                            }
                                        });
                                        $('#result').append.apply($('#result'), items);         
                                    } else {
                                        $('#result').html("Not Found!");
                                    }; 
                                };
                            }
                        });
                    }

                    $('#search_text').keyup(function(){
                        var search = $(this).val();
                        if (search != ''){
                            load_data(search);
                        }else{
                            load_data();
                        }
                    });
                });
            </script>

            <style>
                button.search.collapsed:before 
                { 
                    content:'Search' ; 
                }
                button.search:before
                {
                    content:'Hide Result' ;
                }
            </style>

