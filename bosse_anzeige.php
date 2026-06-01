<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>AJAX</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
    </head>
    <body >
        <h1>Themengebiete:</h1>
        
<?php
        $connection = new mysqli("localhost", "root", "", "elden-ring");
        if ($connection->connect_errno) {
            die("Verbindung fehlgeschlagen: " . $connection->connect_error);
        }

        $sql = "SELECT `id`, `name`, `title`, `hp`, `age_lore`, `size_estimated` From bosses";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "\n\n<ul>\n";
        while ($row = $result->fetch_assoc()) {
            $bossId = $row['id'];
            $bossName = $row['name'];
            $bossTitle = $row['title'];
            $bossHp = $row['hp'];
            $bossAgeLore = $row['age_lore'];
            $bossSizeEstimated = $row['size_estimated'];

            echo "    <li onclick='show_boss_details($bossId)'>$bossName - $bossTitle</li>\n";
        }
        echo "</ul>\n";

        $stmt->close();
        $connection->close();
?>

        
        <h1>Boss-Details:</h1>
        <div id='bossdiv'>Hier kommen dann die Boss-Details rein, 
            wenn ein Boss angeklickt wurde. Die id wird verwendet,
            um das Element zu identifizieren, in das die Boss-Details geladen werden.
        </div>
        
        <script> 
            function show_boss_details(id) {

                let x= new XMLHttpRequest();

                x.onreadystatechange = function() {

                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            let arr  = JSON.parse(this.responseText);

                            let s = formatResultAsHtmlDefinitionList(arr);

                            let element = document.getElementById("bossdiv");
                            element.innerHTML = s;
                            
                        } else {                           
                            alert("Fehler beim Laden der Boss-Details: " + this.status + this.responseText);
                        }
                    }
                }; 

                x.open("POST","bosses_ajax_request.php", true);

                x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                x.send("id="+id);
            }

            function formatResultAsHtmlDefinitionList(arr) {
                let s = "<dl>";   
                for (let i = 0; i< arr.length; i++) {
                    let obj = arr[i];
                    s += "<dt >"+obj.kopfzeile+"</dt>";
                    s += "<dd>"+obj.zusammenfassung+"</dd>";
                }
                return s + "</dl>";
            }
        </script>
    </body>
</html>