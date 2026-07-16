# FPDF

This is a fork of official mirror of the FPDF library. We changed it to
support streaming chunked data. You can avoid memory exceed errors by
using this library.

[Official FPDF Documentation](http://www.fpdf.org/)

## Installation with [Composer](https://packagist.org/packages/setasign/fpdf)

If you're using Composer to manage dependencies, you can use

```
$ composer require arimac/fpdf:^1.8
```

or you can include the following in your composer.json file:

```json
{
    "require": {
        "arimac/fpdf": "^1.8"
    }
}
```

## Usage

- To download a file as a stream

```
<?php
require('fpdf.php');

$pdf = new FPDF();
$pdf->StartDownload('sales_report.pdf');
for($i=0; $i<100000; $i++) {
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',16);
  $pdf->Cell(40,10,'Hello World!');
}
$pdf->Close();
```

Now FPDF will sending the generated data to the browser without waiting
to finish the loop.

- To present a preview of a file as stream

```
require('fpdf.php');

$pdf = new FPDF();
$pdf->StartPreview('sales_report.pdf');
for($i=0; $i<100000; $i++) {
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',16);
  $pdf->Cell(40,10,'Hello World!');
}
$pdf->Close();
```

Same as above method. But it opening the generated PDF file insteed of
downloading.

- To directly write to a file

```
require('fpdf.php');

$pdf = new FPDF();
$pdf->StartFile('sales_report.pdf');
for($i=0; $i<100000; $i++) {
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',16);
  $pdf->Cell(40,10,'Hello World!');
}
$pdf->Close();
```
