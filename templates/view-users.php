<?php 

// Message to show?
$has_message = $_GET['message'] ?: null;
$message_type = $_GET['type'] ?: null;

// Remove user
$user_to_remove = (int) $_GET['remove-user'] ?: null;
if ( $user_to_remove > 0 ) {
    if ( $current_user->type >= 2 ) {
        $user_to_remove = $db->query( "DELETE FROM usuarios WHERE ID = :ID", array( 'ID' => $user_to_remove ) );
        $has_message = 'success';
        $message_type = 'user-removed';
    }
}

$users = $db->query( "SELECT * FROM usuarios" );

get_header(); ?>

<div class="main">
    <div class="header">
        <h1>Relatório de Usuários</h1>
        <a href="?page=main-menu" class="back-button"><i class="fa fa-bars"></i> Menu</a>
        
        <?php 
            if ( $has_message ) {
                switch ( $message_type ) {
                    case 'user-created':
                        $message = 'Usuário criado com sucesso!';
                        break;
                    case 'user-updated':
                        $message = 'Usuário editado com sucesso!';
                        break;
                    case 'user-removed':
                        $message = 'Usuário removido com sucesso!';
                        break;
                    default:
                        $message = 'Sucesso!';
                        break;
                }
                echo "<p class='message {$has_message}'>{$message}</p>";
            }
        ?>
    </div>
    <table class="table-list">
        <thead>
            <tr>
                <th>Nome - Usuário</th>
                <th>Nascimento</th>
                <th>Documentos</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>Curso</th>
                <?php if ( $current_user->type >= 2 ) { ?>
                <th class="action">Alterar</th>
                <th class="action">Excluir</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $users as $user ) : ?>
            <tr>
                <td>
                    <?php echo $user['nome']; ?> - <?php echo $user['usuario'] ?>
                    <small>
                        <?php 
                            echo get_user_type_by_code( $user['tipo'] ); 
                            if ( $user['tipo'] == '1' ) {
                                echo ' - ' . get_level_by_time_spe( $user['tempo_esp'] );
                            }
                        ?>
                    </small>
                </td>
                <td><?php echo datefrommysql( $user['nascimento'] ); ?></td>
                <td><?php echo $user['cpf']; ?><small><?php echo $user['rg']; ?></small></td>
                <td><?php echo $user['endereco']; ?>. <?php echo $user['bairro']; ?> - <?php echo $user['cidade']; ?></td>
                <td><?php echo $user['telefone']; ?></td>
                <td>
                    <?php 
                        if ( $user['curso'] ) {
                            $course = new Course( $user['curso'] );
                            echo $course->data['nome'];
                        } else {
                            echo '---';
                        }
                    ?>
                </td>
                <?php if ( $current_user->type >= 2 ) { ?>
                <td class="action"><a href="?page=edit-user&user_id=<?php echo $user['ID']; ?>" class="edit"><i class="fa fa-pencil"></i></a></td>
                <td class="action">
                    <?php if ( $user['ID'] != $current_user->ID ) { ?>
                    <a href="?page=view-users&remove-user=<?php echo $user['ID']; ?>" class="remove"><i class="fa fa-times"></i></a>
                    <?php } else { ?>
                    ---
                    <?php } ?>
                </td>
                <?php } ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php get_footer(); ?>