<?php

$source = json_decode( file_get_contents( 'goldStandardStems.json' ), true );

$previousForm = '';
$previousStem = '';

// sort by stem; instead of by form?
foreach ( $source['stems'] as list( $form, $stem ) ) {

	// If the stem does not match the previous stem...
	if ( $stem !== $previousStem ) {

		// See how much of the form matches the previous form.
		$matchingCharacters = similar_text( $form, $previousForm, $percentage );
		if ( $percentage > 50 ) {
			printf( '%s [%s] !== %s [%s]', $previousForm, $previousStem, $form, $stem );
			echo PHP_EOL;
		}
	}

	$previousForm = $form;
	$previousStem = $stem;
}
