<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>OpenStack</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">


    </head>
    <body>
        <div id="app">
            <v-app>
                <navbar></navbar>
                <dashboard></dashboard>
                <v-content>
                    <alert :color="this.$store.state.alert.color" :text="this.$store.state.alert.text"></alert>
                    <v-container fluid>
                        <v-fade-transition mode="out-in">
                            <router-view></router-view>
                        </v-fade-transition>
                    </v-container>
                </v-content>
            </v-app>
        </div>
        <div>
            <script src="{{ asset('js/app.js') }}"></script>
        </div>
    </body>
</html>
