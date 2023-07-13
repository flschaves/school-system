<?php 

// Message to show?
$has_message = $_GET['message'] ?: null;
$message_type = $_GET['type'] ?: null;

// Remove course
$course_to_remove = (int) $_GET['remove-course'] ?: null;
if ( $course_to_remove > 0 ) {
    if ( $current_user->type >= 2 ) {
        $query = $db->query( "DELETE FROM cursos WHERE ID = :ID", array( 'ID' => $course_to_remove ) );
        if ( $query > 0 ) {
            $db->query( "UPDATE usuarios SET curso = NULL WHERE curso = :course_id", array( 'course_id' => $course_to_remove) );
        }
        $has_message = 'success';
        $message_type = 'course-removed';
    }
}

$courses = $db->query( "SELECT * FROM cursos" );

get_header(); ?>

<div class="main">
    <div class="header">
        <h1>Relatório de Cursos</h1>
        <a href="?page=main-menu" class="back-button"><i class="fa fa-bars"></i> Menu</a>

        <?php 
            if ( $has_message ) {
                switch ( $message_type ) {
                    case 'course-created':
                        $message = 'Curso criado com sucesso!';
                        break;
                    case 'course-updated':
                        $message = 'Curso editado com sucesso!';
                        break;
                    case 'course-removed':
                        $message = 'Curso removido com sucesso!';
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
                <th>Nome</th>
                <th>Professor</th>
                <th>Descrição</th>
                <th>Sala</th>
                <th>Mensalidade</th>
                <?php if ( $current_user->type >= 2 ) { ?>
                <th class="action">Alterar</th>
                <th class="action">Excluir</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $courses as $course ) : ?>
            <tr>
                <td><?php echo $course['nome']; ?></td>
                <td>
                    <?php 
                        if ( $course['professor'] ) {
                            $user = new User( $course['professor'] );
                            echo $user->data['nome'];
                            echo '<small>' . get_level_by_time_spe( $user->data['tempo_esp'] ) . '</small>';
                        } else {
                            echo '---';
                        }
                    ?>
                </td>
                <td><?php echo $course['descricao']; ?></td>
                <td><?php echo $course['numero_sala']; ?></td>
                <td><?php echo $course['valor_mensalidade']; ?></td>
                <?php if ( $current_user->type >= 2 ) { ?>
                <td class="action"><a href="?page=edit-course&course_id=<?php echo $course['ID']; ?>" class="edit"><i class="fa fa-pencil"></i></a></td>
                <td class="action"><a href="?page=view-courses&remove-course=<?php echo $course['ID']; ?>" class="remove"><i class="fa fa-times"></i></a></td>
                <?php } ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php get_footer(); ?>