<?php
namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;


class QRCodeController extends Controller
{
    public function generateQRCode($data)
    {
        $qrCode = new QrCode('Texte à encoder ici');
        $qrCode->setSize(300);
        
        // Récupérer l'image
        $qrCodeImage = $qrCode->writeString();
    
        // Encoder en base64
        $qrCodeBase64 = base64_encode($qrCodeImage);

        // Retourner le QR code en tant que vue
        return view('clients.dashboard', compact('qrCodeUrl'));
    }
}
