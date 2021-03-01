namespace Google\Cloud\Samples\Vision;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

// $path = 'gs://cloud-samples-data/vision/ocr/sign.jpg'

function detect_text_gcs($path)
{
    $imageAnnotator = new ImageAnnotatorClient();

    # annotate the image
    $response = $imageAnnotator->textDetection($path);
    $texts = $response->getTextAnnotations();

    printf('%d texts found:' . PHP_EOL, count($texts));
    foreach ($texts as $text) {
        print($text->getDescription() . PHP_EOL);

        # get bounds
        $vertices = $text->getBoundingPoly()->getVertices();
        $bounds = [];
        foreach ($vertices as $vertex) {
            $bounds[] = sprintf('(%d,%d)', $vertex->getX(), $vertex->getY());
        }
        print('Bounds: ' . join(', ', $bounds) . PHP_EOL);
    }

    if ($error = $response->getError()) {
        print('API Error: ' . $error->getMessage() . PHP_EOL);
    }

    $imageAnnotator->close();
}
