<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
    <meta name="author" content="">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
    <![endif]-->
</head>
    
<body class="">

<div class="row-fluid"><div class="col col-md-12"><div class="navbar navbar-default navbar-fixed-top navbar-inverse">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
            <span class="icon-bar"></span><span class="icon-bar"></span>
        </button><a class="navbar-brand" href="#">CMS Panel</a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a>
            </li>
            <li><a href="#">Contacts</a>
            </li>
        </ul>
    </div>
</div></div></div><div class="row-fluid" style="margin-top: 70px;"><div class="col col-md-9"><div class="row"><div class="row-fluid"><div class="col col-md-12"><ul class="breadcrumb">
    <li><a href="#">Home</a>
    </li>
    <li><a href="#">Library</a>
    </li>
    <li class="active">Data</li>
</ul></div></div><div class="row-fluid"><div class="col col-md-12"><div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Page Title<br></h3>
    </div>
    <div class="panel-body">
        <?php echo $content; ?>
    </div>
    <div class="panel-footer">Panel footer</div>
</div></div></div></div></div><div class="col col-md-3"><div class="row"><div class="col col-md-12"><ul class="nav nav-pills nav-stacked">
    <li class="active"><a href="#">Home</a>
    </li>
    <li><a href="#">Profile</a>
    </li>
    <li><a href="#">Messages</a>
    </li>
</ul></div></div><div class="row"><div class="col col-md-12"><div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Widgets<br></h3>
    </div>
    <div class="panel-body">
        <p>Panel Content</p>
    </div>
    <div class="panel-footer">Panel footer</div>
</div></div></div></div></div><div class="row-fluid"><div class="col col-md-12 text-center"><p>Copyright 2013. All right reserved.<br></p></div></div></body>

</html>