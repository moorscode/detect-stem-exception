<?php

$source = json_decode( file_get_contents( 'goldStandardStems.json' ), true );

$previousForm = '';
$previousStem = '';

/*
 * This will make detecting exceptional forms easier, if the previous form is closely related to the current form
 * but the stem is different; this is something we want to know and check against.
 */

usort( $source['stems'], function( $a, $b ) {
	// Keep forms sorted by alphabet.
	if ( $a[1] === $b[1] ) {
		return strcmp( $a[0], $b[0] );
	}

	// Sort by Stem.
	return strcmp($a[1], $b[1]);
} );

foreach ( $source['stems'] as list( $form, $stem ) ) {
		if ( $stem !== $previousStem ) {

		// See how much of the form matches the previous form.
		$matchingCharacters = similar_text( $form, $previousForm, $percentage );

		// Percentage in gliding scale relation to word length?
		if ( $matchingCharacters > 2 && $percentage > 50 ) {
			printf( '%s [%s] !== %s [%s]', $previousForm, $previousStem, $form, $stem );
			echo PHP_EOL;

			$found[] = [ $previousForm, $previousStem ];
			$found[] = [ $form, $stem ];
		}
	}

	$previousForm = $form;
	$previousStem = $stem;
}

// 12908 exceptions.
printf( 'Found: %s exceptions.', count( $found ) );

if ( ! empty( $found ) ) {
	file_put_contents( 'unexpectedForms.js', json_encode( $found, JSON_UNESCAPED_UNICODE ) );
}
