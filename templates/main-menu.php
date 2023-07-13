<?php get_header(); ?>

<div class="main">
    <h1>Bem vindo <?php echo get_user_type_by_code( $current_user->type ); ?>!</h1>
    <p class="legend">Escolha uma das opções abaixo:</p>
    <nav class="menu">
        <ul>
            <?php if ( $current_user->type >= 2 ) { // Administrador ?>
            <li><a href="?page=view-users"><i class="fa fa-user"></i>Visualizar Usuários</a></li>
            <li><a href="?page=edit-user"><i class="fa fa-user-plus"></i>Cadastrar Usuário</a></li>
            <li><a href="?page=edit-course"><i class="fa fa-graduation-cap"></i>Cadastrar Curso</a></li>
            <?php } if ( $current_user->type >= 1 ) { // Professor + ?>
            <li><a href="?page=view-courses"><i class="fa fa-book"></i>Visualizar Cursos</a></li>
            <li><a href="?page=edit-note"><i class="fa fa-puzzle-piece"></i>Cadastrar Nota</a></li>
            <?php } if ( $current_user->type >= 0 ) { // Usuário + ?>
            <li><a href="?page=view-notes<?php echo $current_user->type == 0 ? '&user_id=' . $current_user->ID : ''; ?>"><i class="fa fa-sort-numeric-asc"></i>Visualizar Notas</a></li>
            <?php } ?>
            <li><a href="?logout"><i class="fa fa-sign-out"></i>Sair</a></li>
        </ul>
    </nav>
</div>

<?php get_footer(); ?>