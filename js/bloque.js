/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */

( function( blocks, components, element, data ) {
    var el = element.createElement;
    var Fragment = element.Fragment;
    var SelectControl = components.SelectControl;
    var withSelect = data.withSelect;
    var TextControl = components.TextControl;
    var Button = components.Button;

    blocks.registerBlockType( 'mi-tema/bloque1', {
        title: 'Mi Bloque Personalizado',
        icon: 'universal-access-alt',
        category: 'layout',
        attributes: {
            selectedItems: {
                type: 'array',
                default: [],
            },
            search: {
                type: 'string',
                default: '',
            },
        },
        edit: withSelect( function( select, ownProps ) {
            var query = ownProps.attributes.search ? { search: ownProps.attributes.search } : {};
            var posts = select( 'core' ).getEntityRecords( 'postType', 'post', { per_page: -1, ...query } );
            var pages = select( 'core' ).getEntityRecords( 'postType', 'page', { per_page: -1, ...query } );

            var allItems = [];

            if (posts) {
                allItems = allItems.concat(posts.map(function(post) {
                    var thumbnailId = get_post_thumbnail_id(post.id); // Obtener la ID de la imagen destacada
                    var thumbnailUrl = wp_get_attachment_image_src(thumbnailId, 'full')[0]; // Obtener la URL de la imagen destacada

                    return {
                        label: post.title.rendered,
                        value: post.id,
                        type: 'post',
                        thumbnailUrl: thumbnailUrl // Agregar la URL de la imagen destacada
                    };
                }));
            }
            if ( pages ) {
                allItems = allItems.concat( pages.map( function( page ) {
                    return { label: page.title.rendered, value: page.id, type: 'page' };
                } ) );
            }

            return {
                allItems: allItems,
            };
        } )( function( props ) {
            var selectedItems = props.attributes.selectedItems;
            var allItems = props.allItems;

            function onSelectItem( selectedItemValue ) {
                var selectedItem = allItems.find( item => item.value.toString() === selectedItemValue );
                if ( selectedItem && !selectedItems.find( item => item.value === selectedItem.value ) ) {
                    props.setAttributes( { selectedItems: [...selectedItems, selectedItem] } );
                }
            }

            function onSearchChange( search ) {
                props.setAttributes( { search: search } );
            }

            function onDeleteItem( itemToDelete ) {
                var newSelectedItems = selectedItems.filter( item => item.value !== itemToDelete.value );
                props.setAttributes( { selectedItems: newSelectedItems } );
            }

            return el( Fragment, {},
                el( TextControl, {
                    value: props.attributes.search,
                    placeholder: 'Buscar entradas o páginas...',
                    onChange: onSearchChange,
                } ),
                el( SelectControl, {
                    label: 'Selecciona entradas o páginas:',
                    options: allItems.map( item => ({ label: item.label, value: item.value.toString() }) ),
                    onChange: onSelectItem,
                } ),
                el( 'ul', {},
                    selectedItems.map( function( item ) {
                        return el( 'li', {},
                            item.label + ' (' + item.type + ') ',
                            el( Button, {
                                isSmall: true,
                                isDestructive: true,
                                onClick: function() { onDeleteItem(item); },
                            }, 'Eliminar' )
                        );
                    } )
                )
            );
        } ),
        save: function( props ) {
            return el( 'ul', {},
                props.attributes.selectedItems.map( function( item ) {
                    return el( 'li', {}, item.label + item.thumbnailUrl + ' (' + item.type + ')' );
                } )
            );
        },
    } );
} )(
    window.wp.blocks,
    window.wp.components,
    window.wp.element,
    window.wp.data
);


const { registerBlockType } = wp.blocks;
const { TextControl } = wp.components;
const { useState } = wp.element;
const { select } = wp.data;

registerBlockType('mi-tema/bloque', {
    title: 'Featured Image Block',
    icon: 'format-image',
    category: 'common',
    attributes: {
        postId: {
            type: 'number',
            default: 0,
        },
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const [postTitle, setPostTitle] = useState('');

        // Función para obtener el título del post seleccionado
        const getPostTitle = () => {
            const post = select('core').getEntityRecord('postType', 'post', attributes.postId);
            if (post && post.title) {
                setPostTitle(post.title.raw);
            } else {
                setPostTitle('');
            }
        };

        // Actualizar el título del post al cambiar el ID del post
        const onPostIdChange = (value) => {
            setAttributes({ postId: parseInt(value) });
            getPostTitle();
        };

        return (
            <div>
                <TextControl
                    label="Select Post ID"
                    value={attributes.postId.toString()}
                    onChange={onPostIdChange}
                />
            </div>
        );
    },
    save: function() {
        return null; // No se requiere una vista previa en el editor.
    },
});
