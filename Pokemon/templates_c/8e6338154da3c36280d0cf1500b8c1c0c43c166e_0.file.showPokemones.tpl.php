<?php
/* Smarty version 4.3.0, created on 2023-02-17 15:44:28
  from 'C:\xampp\htdocs\web2\Finalweb2\Ejercicios\Pokemon\templates\showPokemones.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_63ef92cca66a45_50401052',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8e6338154da3c36280d0cf1500b8c1c0c43c166e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\web2\\Finalweb2\\Ejercicios\\Pokemon\\templates\\showPokemones.tpl',
      1 => 1676645044,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_63ef92cca66a45_50401052 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<ul>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pokemones']->value, 'pokemon');
$_smarty_tpl->tpl_vars['pokemon']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['pokemon']->value) {
$_smarty_tpl->tpl_vars['pokemon']->do_else = false;
?>
    <li><?php echo $_smarty_tpl->tpl_vars['pokemon']->value->apodo;?>
</li>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</ul>

<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
