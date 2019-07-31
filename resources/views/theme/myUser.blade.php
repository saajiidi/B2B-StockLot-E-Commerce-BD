@extends('theme.default')



@section('content')

    <div class="row">

        <div class="col-lg-12">

            <h1 class="page-header">My Users</h1>

        </div>

        <!-- /.col-lg-12 -->

    </div>

    <!-- /.row -->



    <table class="table table-striped table-bordered table-hover">

        <thead>

        <tr>

            <th>#</th>

            <th>First Name</th>

            <th>Last Name</th>

            <th>Username</th>

        </tr>

        </thead>

        <tbody>

        <tr>

            <td>1</td>

            <td>Hardik</td>

            <td>Savani</td>

            <td>@mdo</td>

        </tr>

        <tr>

            <td>2</td>

            <td>Kashiyani</td>

            <td>Vimal</td>

            <td>@fat</td>

        </tr>

        <tr>

            <td>3</td>

            <td>Harshad</td>

            <td>Pathak</td>

            <td>@twitter</td>

        </tr>

        </tbody>

    </table>



@endsection
