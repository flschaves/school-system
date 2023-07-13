;(function( $, window, undefined ){

    'use strict';

    // Hide message after 3 seconds
    setTimeout( function(){
        $( '.header .message' ).fadeOut( 'fast' );
    }, 3000);

    // Mask inputs
    $( '#nascimento, #data_avaliacao' ).mask( '99/99/9999' );
    $( '#cpf' ).mask( '999.999.999-99' );
    $( '#telefone' ).mask( '(99) 9999-99999' );

    // Confirm excludes
    $( '.remove' ).on( 'click', function( event ) {
        if ( ! confirm( 'Tem certeza que deseja deletar?' ) ) {
            return false;
        }
    });

    // Hide fields by user type
    $( '#tipo' ).on( 'change', changeInputsOnUserRegisterForm) ;
    changeInputsOnUserRegisterForm();

    // Focus on first field ever
    $( 'input:text:visible:first' ).focus();

}( jQuery, window ));

function changeInputsOnUserRegisterForm() {
    
    var val = $( '#tipo' ).val();
    $( 'label:hidden' ).show();
    if ( val == '0' ) {
        $( '#tempo_esp' ).closest( 'label' ).hide();
    } else if ( val == '1' ) {
        $( '#curso' ).closest( 'label' ).hide();
    } else if ( val == '2' ) {
        $( '#curso, #tempo_esp' ).closest( 'label' ).hide();
    }
}