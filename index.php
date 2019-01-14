<!DOCTYPE html> 
<!--
        Author : Mateusz Szymanski

         Functionality:
         Display URL of requested website
         Display the <title> of the page
         Display number of links on the page that the user can click on
         Display all of the links 
         Display number of unique domains that these links go to
         Display all of the unique links found
         Check if page was served in a secure mannered
         Display SSL certificate information
         Was Google Analytics available on the page?       
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="stylee.css"> 
        <title></title>
    </head>
    <body>
        <div id="get_page">

            <form  method="post">
                <p>
                    <label for="link" style="font-weight: bold;">URL:</label>
                    <input type="url" name="URL"> <!--url-->
                    <input type="submit" value="Submit" > 
                </p>
            </form>
        </div> 

        <div id="main_content">
            <table>
                <?php
                include 'get_data.php';
                
                //--------------------Website URL---------------------------
                echo '<tr>
                        <td>URL:</td>
                        <td>' .$url . '</td>
                    </tr>';

                //---------------------Website Titile-----------------------
                echo '<tr>
                        <td>Website Title: </td>
                        <td>' . page_title($dom) . '</td>
                    </tr> ';

                //----------------------Number of links---------------------
                echo '<tr>
                        <td>Number Of Links:</td>
                        <td>' . numb_of_links($dom) . '</tr>';
                
                //--------------------Print all of the retrieved links----------
                //Get array of available links
                $links = all_links($dom);
                echo '<tr> 
                        <td>All Links:</td>
                        <td>
                            <button onclick="toggleLinks()">Show/Hide</button>                    
                            <div id="linksList">';
                            foreach ($links as $link) {
                            echo $link . '<br>';
                            }
                            '</div>
                        </td>
                    </tr>';

                //------------------Number of unique domains------------------- 
                echo '<tr>
                        <td>Number Of Unique Domains:</td>
                        <td>'. numb_of_unique_domains($dom) . '</td>      
                     </tr>';

                //--------------------------Print unique domains----------------
                $domains = unique_domains($dom);
                echo '<tr>
                        <td>Unique Domains: </td>
                        <td>
                            <button onclick="toggleUnique()">Show/Hide</button>                    
                            <div id="linksUnique">';                             

                            foreach ($domains as $domain) {
                                if($domain == '/'){
                                    $domain= $url;
                                }
                                
                            echo $domain . '<br>';
                            }
                            '</div>
                             </div>
                        </td> 
                     </tr>';
                            
                //--------Was the page was served in a secure manner------------
                $sslInfo = secure_page($url);
                echo '<tr> 
                        <td>Was the page was served in a secure manner?</td>
                        <td>';
                //Check SS for verify ok.  
                        foreach ($sslInfo as $ssl) {
                            if(strpos($ssl, 'verify ok.')!==false){
                                echo 'Yes';
                            }
                            }
                        '</td>
                      </tr>';
                            
                //-------------------Certificate Information--------------------                    
                 $sslInfo = secure_page($url);
                 //Remove insignificant information from the array
                 $sslInfo = array_diff_key($sslInfo, ["0","1","2","3","4","5","6","7"]);
                echo '<tr> 
                        <td>Certificate Information:</td>
                        <td>
                            <button onclick="toggleSecure()">Show/Hide</button>                    
                            <div id="securePage">';                             

                            foreach ($sslInfo as $ssl) {
                            echo $ssl . '<br>';
                            }
                            '</div>
                             </div></td>
                      </tr>';
                //-------------------Google analytics available ?---------------
                $analytics = google_analytics($dom);
                echo '<tr> 
                    <td>Google Analytics Available ? </td>
                    <td>' . $analytics . '</td>
                        </tr>';
                ?>
            </table>
        </div>       

        <script>
            //Toggle Show/Hide Links
            function toggleLinks() {
                var x = document.getElementById("linksList");
                if (x.style.display === "block") {
                    x.style.display = "none";
                } else {
                    x.style.display = "block";
                }
            }

            //Toggle Show/Hide Unique Links
            function toggleUnique() {
                var x = document.getElementById("linksUnique");
                if (x.style.display === "block") {
                    x.style.display = "none";
                } else {
                    x.style.display = "block";
                }
            }
            
            //Toggle Show/Hide SSL data
            function toggleSecure() {
                var x = document.getElementById("securePage");
                if (x.style.display === "block") {
                    x.style.display = "none";
                } else {
                    x.style.display = "block";
                }
            }
        </script>        
    </body>
</html>
