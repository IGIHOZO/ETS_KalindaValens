<?php require("main/view.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Staff ID Card</title>
</head>

<body>
    <?php
    $sel = $con->prepare("SELECT * FROM ets_workers WHERE ets_workers.worker_status=1 ORDER BY ets_workers.worker_id DESC");
    $sel->execute();
    if ($sel->rowCount() >= 1) {
        $cnt = 1;
        while ($ft_se = $sel->fetch(PDO::FETCH_ASSOC)) {
            if ($MainView->WorkerPositionName($ft_se['worker_id']) == '-' && $ft_se['worker_category'] == 3) {
                $position = 'Digger';
            } else {
                $position = $MainView->WorkerPositionName($ft_se['worker_id']);
            }
            $fullNames = strtoupper($ft_se['worker_fname']) . "_" . ucfirst(strtolower($ft_se['worker_lname']));
    ?>

            <div style="position: relative;">
                <table id="card<?= $ft_se['worker_id'] ?>" style="width: 300px; height: 200px; margin: 10px auto; border-collapse: collapse; outline: 2px solid #3498db; border-radius: 10px; position: relative;">
                    <tbody>
                        <tr>
                            <td colspan="3" style="text-align: center; background-color: #3498db; color: #fff; padding: 4px; font-weight: bolder; font-size: 15px;">
                                <h4 style="margin: 0;">MINE ETS Kalinda Valens</h4>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding: 20px; position: relative;">
                                <img src="<?= $ft_se['worker_photo'] ?>" alt="Employee profile picture" style="width: 80px; height: 80px; border-radius: 50%; border: 4px solid #3498db;" />
                                <h5 style=" font-size: 16px; color: #333;"><?= strtoupper($ft_se['worker_fname']) . ' ' . $ft_se['worker_lname'] ?> </h5>
                                <b><i><label style="margin: 5px 0; color: #666;"><?= $position ?></label></i></b>
                            </td>
                            <td style="width: 160px; padding: 0px 40px 0px 40px; text-align: center; color: #333;font-weight:bold">
                                <div style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; background-image: url('img/logo.jpeg'); background-size:50%; background-position: center; opacity: 0.1; z-index: -1;background-repeat:no-repeat"></div>

                            <span>
                            <p style="font-size: 14px; font-weight: bold;"><b><h3><?= $ft_se['worker_unid'] ?></h3></b></p>
                                <p style="margin: 10px 0; font-size: 12px;">
                                    <i class="fa fa-phone-alt" style="color: #3498db; margin-right: 5px;"></i> +25<?= $ft_se['worker_phone'] ?>
                                </p>
                                <p style="font-size: 12px;">
                                    <i class="fa fa-envelope" style="color: #3498db; margin-right: 5px;"></i> info@etskalindavalens.com
                                </p>
                                <p style="font-size: 12px;">
                                    <i class="fa fa-globe" style="color: #3498db; margin-right: 5px;"></i> etskalindavalens.com
                                </p>
                                <p style="font-size: 12px;">
                                    <i class="fa fa-map-marker-alt" style="color: #3498db; margin-right: 5px;"></i> 3VQP+JV, Taba
                                </p>
                            </span>
                            </td>
                            <td style="text-align: center; padding: 20px; position: relative;font-size: 9px;font-weight:bold;color:#000 ">
                            <div id="qr-code-<?= $ft_se['worker_id'] ?>"></div>
                            <script>
                                var qrcode = new QRCode(document.getElementById("qr-code-<?= $ft_se['worker_id'] ?>"), {
                                    text: "http://localhost/ets/reception.php?userAttend=1&attendedUser=<?= $ft_se['worker_id'] ?>", // Fixed typo here
                                    width: 120,
                                    height: 120,
                                    colorDark: "#000000",
                                    correctLevel: QRCode.CorrectLevel.H
                                });
                            </script>



                                <small style=" display: block; margin-top: 10px; color: #666;">This card is the property of ETS</small>
                                <p class="issue-date" style="margin: 10px 0; color: #333;">Issued Date: 2024-02-21</p>
                                <p class="expiry-date" style="margin: 10px 0; color: #333;">Expiry Date: 2025-02-21</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; background-color: #3498db; color: #fff; font-weight: bolder; font-size: 16px;">
                                <label>Rubare village, Gishyeshye, Rukoma, Kamonyi District</label>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <!-- Add the Bootstrap download button outside the table -->
                <button style="margin-top: 10px; padding: 8px 16px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;" onclick="downloadImage('card<?= $ft_se['worker_id'] ?>','<?=$fullNames?>')">Download Card</button>
            </div>

            <script>
                function downloadImage(cardId, names) {
                    html2canvas(document.querySelector(`div #${cardId}`), { useCORS: true }).then(canvas => {
                        var link = document.createElement('a');
                        link.href = canvas.toDataURL();
                        link.download = `card_${names}.png`;
                        link.click();
                    });
                }
            </script>

    <?php
            $cnt++;
        }
    } else {
    ?>
        <tr>
            <td colspan="7">
                <center>No data found ...</center>
            </td>
        </tr>
    <?php
    }
    ?>
</body>

</html>
