<!DOCTYPE html>
<html>
    <head></head>
    <body class="text-center" style="background: #f1f1f1">
        <div class="m-b-md">
        <h3 class="m-b-none">{{ response.getStatusCode() }}</h3>
        </div>
        <div class="panel-body">
        {{ partial('common/lopy_format') }}
        <pre>{{ message }}</pre>
        </div>
    </body>
</html>
