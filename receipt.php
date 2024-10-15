<?php

session_start();
// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    echo "
    <script>
        alert('You must log in first!');
        window.location.href = 'login.php';
    </script>
    ";
    exit; // Stop further execution
}

include "config.php";
$busId = "";
$busname = "";
$pickup = "";
$journey = "";
$drop = "";
$price =  "";
$from = "";
$to = "";

if (isset($_GET['busId']) && isset($_GET['busname']) && isset($_GET['pickup']) && isset($_GET['journey']) && isset($_GET['drop']) && isset($_GET['price']) && isset($_GET['from']) && isset($_GET['to'])) {
    $busId = $_GET['busId'];
    $busname = $_GET['busname'];
    $pickup = $_GET['pickup'];
    $journey = $_GET['journey'];
    $drop = $_GET['drop'];
    $price = $_GET['price'];
    $from = $_GET['from'];
    $to = $_GET['to'];
}

$seatNumbers = array(
    "one",
    "two",
    "three",
    "four",
    "five",
    "six",
    "seven",
    "eight",
    "nine",
    "ten",
    "eleven",
    "twelve",
    "thirteen",
    "fourteen",
    "fifteen",
    "sixteen",
    "seventeen",
    "eighteen",
    "nineteen",
    "twenty",
    "twentyone"
);

$randomNumber1 = rand(10, 99);
$randomNumber2 = rand(10, 99);
function getRandomCharacter($characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
{
    $index = rand(0, strlen($characters) - 1);
    return $characters[$index];
}
$randomChar1 = getRandomCharacter();
$randomChar2 = getRandomCharacter();
$unqiueNumber = $randomNumber1 . $randomChar1 . $randomNumber2 . $randomChar2;

$arraySeats = array_fill(0, 21, 0);

$array = array_fill(0, 21, 0);

$query = "";
$count = 0;
for ($i = 0; $i < 21; $i += 1) {
    if (isset($_GET[$seatNumbers[$i]])) {
        $arraySeats[$i] = 1;
        //echo $seatNumbers[$i];
        $array[$count++] = $i + 1;
        $query .= "`" . $seatNumbers[$i] . "`=1 ,";
        //echo $query;
    }
}

$len = strlen($query);
$query = substr($query, 0, $len - 2);

$query1 = "UPDATE `seatdetail` SET $query WHERE `busid`= $busId ";

// Establish the database connection
$con = mysqli_connect('localhost', 'root', '', 'user');

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Execute the query
if (mysqli_query($con, $query1)) {
    $seats = array();
    $count = 0;
    for ($i = 0; $i < 21; $i++) {
        if ($arraySeats[$i] == 1) {
            $seats[$count] = $i + 1;
            $count++;
        }
    }
    $total = 0;
?>
<?php
} else {
    echo "Error updating record: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamar Bus Ticket Booking Service</title>
    <link rel="icon" type="image/icon" href="Images/hamar-icon.ico">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.2/html2pdf.bundle.min.js"></script>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: white;
            height: 100vh;
        }

        .details {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            font-size: 1.8rem;
            min-height: 100vh;
            box-sizing: border-box;
        }

        #download {
            background-color: rgb(232, 167, 38);
            border: 2px solid black;
            color: rgb(0, 0, 0);
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 20px;
            transition: background-color 0.25s, box-shadow 0.3s ease;
            cursor: pointer;
            transition: transition 0.3s;
        }

        #download:hover {
            background-color: rgb(255, 227, 104);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transform: scale(1.1);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid black;
            font-size: 1rem;
            word-wrap: break-word;
        }

        @media (max-width: 768px) {
            .details {
                font-size: 1.2rem;
                padding: 10px;
                min-height: auto;
                /* Ensure the content is not forced to 100vh */
            }

            #download {
                font-size: 0.9rem;
                padding: 8px 16px;
            }

            table th,
            table td {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .details {
                font-size: 1rem;
                padding: 10px;
                /* Reduce padding */
                min-height: auto;
                /* Remove the forced height */
            }

            #download {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            table {
                width: 100%;
                font-size: 0.8rem;
            }

            table th,
            table td {
                font-size: 0.8rem;
                padding: 6px;
            }
        }
    </style>

</head>

<body>
    <div class="details" id="invoice">
        <h2>BUS BOOKING CONFIRMED !!</h2>

        <table border="3">
            <tr>
                <th>Data</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Unique Ticket ID</td>
                <td><?php echo $unqiueNumber ?></td>
            </tr>
            <tr>
                <td>Bus ID</td>
                <td><?php echo $busId ?></td>
            </tr>
            <tr>
                <td>Bus Name</td>
                <td><?php echo $busname ?></td>
            </tr>
            <tr>
                <td>Bus Pickup Time</td>
                <td><?php echo $pickup ?></td>
            </tr>
            <tr>
                <td>Bus Drop Time</td>
                <td><?php echo $drop ?></td>
            </tr>
            <tr>
                <td>Bus Total Journey Duration</td>
                <td><?php echo $journey ?></td>
            </tr>
            <tr>
                <td>Bus Pickup Location</td>
                <td><?php echo $from ?></td>
            </tr>
            <tr>
                <td>Bus Destination Location</td>
                <td><?php echo $to ?></td>
            </tr>
            <tr>
                <td>Seat Numbers Booked</td>
                <td><?php
                    for ($i = 0; $i < 21; $i++) {
                        if ($array[$i] != 0) {
                            echo $array[$i] . " ";
                        }
                    }
                    ?></td>
            </tr>
            <tr>
                <td>Total Amount Paid</td>
                <td><?php echo "₹" . $price ?></td>
            </tr>
        </table>
        <button id="download">Print Ticket</button>
    </div>
    <script>
        window.onload = function() {
            document.getElementById("download").addEventListener("click", () => {
                const invoice = document.getElementById("invoice");
                const opt = {
                    margin: 1,
                    filename: 'booking-confirmation.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'letter',
                        orientation: 'portrait'
                    }
                };
                html2pdf().from(invoice).set(opt).save();
                setTimeout(() => {
                    window.location.href = "index1.php";
                }, 2000);
            })
        }

        document.addEventListener("DOMContentLoaded", function() {
            const resizableBox = document.getElementById("resizable-box");
            resizableBox.addEventListener("input", function() {
                // Adjust the size of the box based on the content
                resizableBox.style.width = resizableBox.scrollWidth + "px";
                resizableBox.style.height = resizableBox.scrollHeight + "px";
            });
        });
    </script>
</body>

</html>