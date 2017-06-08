<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$subscription_ids = array('cy4Zph-RfPc:APA91bFx_uY9dOa7tiMijM1nk-zPQMSgxodeMy_mM0pNujuptWTyFsf_h3Oghba2nA6f-NIEp4BHG26S9oQ29454lXk2Qfv58i4fa-sCdQpdKjVSUbmCZOU9VIckrGs3ZeqbfZlZtyts');
send_push_message($subscription_ids);

function send_push_message($subscription_ids) {
// Set GCM endpoint
    $url = 'https://fcm.googleapis.com/fcm/send';


    $fields = array(
        'registration_ids' => $subscription_ids,
    );

    $headers = array(
        'Authorization: key=' . 'AAAA4FqgFYM:APA91bGPbDcFHJMccNYltIhl3-bwArXjQjqMeem2qb0OmK9tiZStedVoaWI4Ry2BmCdLGSAJZpaZ0kcJLKn8izSdmFi_HeRC5N_dbNcYGg3aGCU4o2O5wXYGJO9VxkhPlXGpM3aD9Em5',
        'Content-Type: application/json'
    );

    $ch = curl_init();

// Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
    $result = curl_exec($ch);
    var_dump($result);
    if ($result === FALSE) {
        die('Push msg send failed in curl: ' . curl_error($ch));
    }

// Close connection
    curl_close($ch);
}
