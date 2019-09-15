<!DOCTYPE html>
<html lang="sk">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>BP</title>
	<link href="css/font-face.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
	<link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
	<link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
	<link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
	<link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
	<link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
	<link href="css/theme.css" rel="stylesheet" media="all">
    <link href="https://unpkg.com/vue-cal/dist/vuecal.css" rel="stylesheet">
    {{--    <link rel="stylesheet" href="vue-datetime.css">--}}
</head>

<body>
	<div id="app">
		<app></app>
	</div>

	<script src="{{ mix('js/app.js') }}"></script>
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                "language": {
                    "lengthMenu": "_MENU_ položiek na jednej strane",
                    "zeroRecords": "Pre vyhľadavaný výraz sa nenašli žiadne výsledky",
                    "info": "_PAGE_ z _PAGES_",
                    "infoEmpty": "Žiadne výsledky",
                    "infoFiltered": "",
                    "search": "Zadaj výraz pre vyhľadávanie:",
                    "oPaginate": {
                        "sPrevious": "Späť", // This is the link to the previous page
                        "sNext": "Ďalej", // This is the link to the next page
                    }
                }
            });
        });
    </script>
    <script src="vendor/select2/select2.min.js"></script>
	<script src="js/main.js"></script>
    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/vue-cal"></script>

</body>

</html>
