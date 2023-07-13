<?php 

$user_id = (int) $_GET['user_id'] ?: null;

if ( $user_id > 0 ) {
    $user = new User( $user_id );
}

$courses = $db->query( "SELECT * FROM cursos" );

$required_fields = array(
    'nome', 'usuario', 'nascimento', 'rg', 'cpf',
    'endereco', 'cidade', 'telefone', 
    'tempo_esp', 'tipo', 'curso', 're-password'
);
if ( isset( $_POST['action'] ) ) {

    // Validate required fields
    $error = false;
    foreach ( $required_fields as $field ) {

        // re-password just required if password is not empty
        if ( $field == 're-password' and empty( $_POST['password'] ) ) {
            continue;
        }
        // curso just required for user level 0
        if ( $field == 'curso' and $_POST['tipo'] != '0' ) {
            continue;
        }
        // tempo_esp just required for user level 1
        if ( $field == 'tempo_esp' and $_POST['tipo'] != '1' ) {
            continue;
        }

        // verify if other fields is empty
        if ( $_POST[ $field ] == '' ) {
            $required_fields[ $field ] = '<span class="single-error">Preencha este campo!</span>';
            $error = true;
        }
    }

    // Verify if username already exists
    if ( ! empty( $_POST['usuario'] ) ) {
        $username = $db->get_row( "SELECT usuario, ID FROM usuarios WHERE usuario = :usuario", array( 'usuario' => $_POST['usuario'] ) );
        if ( $username ) {
            if ( $username['ID'] != $user->ID ) {
                $required_fields['usuario'] = '<span class="single-error">Usuário já existe!</span>';
                $error = true;
            }
        }
    }

    // Validate password
    if ( ! empty( $_POST['password'] ) and ! empty( $_POST['re-password'] ) ) {
        if ( $_POST['password'] != $_POST['re-password'] ) {
            $required_fields['re-password'] = '<span class="single-error">As senhas não combinam!</span>';
            $error = true;
        }
    }

    // If there's no errors, update or insert user
    if ( ! $error ) {
        $user_data = $_POST;

        // Is updating existent user
        if ( $user_id > 0 ) {
            $user_data['ID'] = $user_id;
        }

        // Format fields to insert in database
        $user_data['nascimento'] = date2mysql( $user_data['nascimento'] );

        // If password changed
        if ( ! empty( $user_data['password'] ) ) {
            $user_data['senha'] = $user_data['password'];
        }

        // Insert or update user
        $new_user_id = insert_user( $user_data );

        if ( $new_user_id > 0 ) {
            $type = 'user-created';
            if ( $user_data['ID'] > 0 ) {
                $type = 'user-updated';
            }
            redirect( 'view-users', '&message=success&type=' . $type );
        }
    }
}

get_header(); ?>

<div class="main">
    <div class="header">
        <h1><?php echo $user_id > 0 ? 'Editar' : 'Cadastrar' ?> Usuário</h1>
        <a href="?page=main-menu" class="back-button"><i class="fa fa-bars"></i> Menu</a>
    </div>
    <form action="" method="post" class="form-two">
        <p>
            <label for="nome">Nome <?php echo $required_fields['nome']; ?><br>
                <input type="text" name="nome" id="nome" class="input" value="<?php echo ( $_POST['nome'] ?: $user->data['nome'] ) ?: ''; ?>" size="45">
            </label>
        </p>
        <p>
            <label for="usuario">Usuário <?php echo $required_fields['usuario']; ?><br>
                <input type="text" name="usuario" id="usuario" class="input" value="<?php echo ( $_POST['usuario'] ?: $user->data['usuario'] ) ?: ''; ?>" size="40">
            </label>
        </p>
        <p>
            <label for="nascimento">Nascimento <?php echo $required_fields['nascimento']; ?><br>
                <input type="text" name="nascimento" id="nascimento" class="input" value="<?php echo ( $_POST['nascimento'] ?: $user->data['nascimento'] ) ? datefrommysql( ( $_POST['nascimento'] ?: $user->data['nascimento'] ) ) : ''; ?>" size="20">
            </label>
        </p>
        <p>
            <label for="rg">RG <?php echo $required_fields['rg']; ?><br>
                <input type="text" name="rg" id="rg" class="input" value="<?php echo ( $_POST['rg'] ?: $user->data['rg'] ) ?: ''; ?>" size="11">
            </label>
        </p>
        <p>
            <label for="cpf">CPF <?php echo $required_fields['cpf']; ?><br>
                <input type="text" name="cpf" id="cpf" class="input" value="<?php echo ( $_POST['cpf'] ?: $user->data['cpf'] ) ?: ''; ?>" size="11">
            </label>
        </p>
        <p>
            <label for="endereco">Endereço <?php echo $required_fields['endereco']; ?><br>
                <input type="text" name="endereco" id="endereco" class="input" value="<?php echo ( $_POST['endereco'] ?: $user->data['endereco'] ) ?: ''; ?>" size="200">
            </label>
        </p>
        <p>
            <label for="bairro">Bairro<br>
                <input type="text" name="bairro" id="bairro" class="input" value="<?php echo ( $_POST['bairro'] ?: $user->data['bairro'] ) ?: ''; ?>" size="50">
            </label>
        </p>
        <p>
            <label for="cidade">Cidade <?php echo $required_fields['cidade']; ?><br>
                <input type="text" name="cidade" id="cidade" class="input" value="<?php echo ( $_POST['cidade'] ?: $user->data['cidade'] ) ?: ''; ?>" size="100">
            </label>
        </p>
        <p>
            <label for="complemento">Complemento<br>
                <input type="text" name="complemento" id="complemento" class="input" value="<?php echo ( $_POST['complemento'] ?: $user->data['complemento'] ) ?: ''; ?>" size="200">
            </label>
        </p>
        <p>
            <label for="telefone">Telefone <?php echo $required_fields['telefone']; ?><br>
                <input type="text" name="telefone" id="telefone" class="input" value="<?php echo ( $_POST['telefone'] ?: $user->data['telefone'] ) ?: ''; ?>" size="20">
            </label>
        </p>
        <p>
            <label for="tipo">Tipo de usuário<br>
                <select name="tipo" id="tipo">
                    <option value="0" <?php selected( ( $_POST['tipo'] ?: $user->data['tipo'] ), '0' ); ?>>Aluno</option>
                    <option value="1" <?php selected( ( $_POST['tipo'] ?: $user->data['tipo'] ), '1' ); ?>>Professor</option>
                    <option value="2" <?php selected( ( $_POST['tipo'] ?: $user->data['tipo'] ), '2' ); ?>>Administrador</option>
                </select>
            </label>
        </p>
        <p>
            <label for="tempo_esp">Tempo de especialização <?php echo $required_fields['tempo_esp']; ?><br>
                <input type="number" name="tempo_esp" id="tempo_esp" class="input" value="<?php echo ( $_POST['tempo_esp'] ?: $user->data['tempo_esp'] ) ?: ''; ?>" size="2">
            </label>
            <label for="curso">Curso <?php echo $required_fields['curso']; ?><br>
                <select name="curso" id="curso">
                    <option value="">Selecione</option>
                    <?php foreach ( $courses as $course ) : ?>
                    <option value="<?php echo $course['ID'] ?>" <?php selected( ( $_POST['curso'] ?: $user->data['curso'] ), $course['ID'] ); ?>><?php echo $course['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p>
            <label for="password">Senha <small>deixe em branco para não alterar</small><br>
                <input type="password" name="password" id="pasword" class="input" value="" size="20">
            </label>
        </p>
        <p>
            <label for="re-pasword">Repetir Senha <?php echo $required_fields['re-password']; ?> <small>deixe em branco para não alterar</small><br>
                <input type="password" name="re-password" id="re-pasword" class="input" value="" size="20">
            </label>
        </p>
        <p class="submit">
            <button class="button button-primary">Salvar</button>
        </p>
        <input type="hidden" name="action" value="<?php echo $user_id > 0 ? 'update' : 'create'; ?>">
    </form>
</div>

<?php get_footer(); ?>