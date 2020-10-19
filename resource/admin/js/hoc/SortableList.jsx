/*
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

import {Component} from "@wordpress/element";
import arrayMove from "array-move";
import {SortableContainer, SortableElement, SortableHandle} from "react-sortable-hoc";

export function useSortableList()
{
    class SortableListComponent extends Component
    {
        render()
        {
            return (
                    <div className="tw-sortable-list">
                        {this.props.items.map((value, index) => (
                                <SortableListItem
                                        key={"item-" + index}
                                        index={index}
                                        value={value}
                                        onDelete={this.props.onDelete}/>
                        ))}
                    </div>
            );
        }
    }

    const SortableListItem = SortableElement(({value, onDelete}) => (
            <div className="tw-sortable-list-item">
                <SortableListItemHandle/>
                <div className="tw-sortable-list-item-body">
                    {value.title && (<strong>{value.title}</strong>)}
                    {value.description && (<span>{value.description}</span>)}
                </div>
                {onDelete && (
                        <a className="tw-sortable-list-item-delete" onClick={() => onDelete(value)}>
                            <svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
                                <path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
                            </svg>
                        </a>
                )}
            </div>
    ));

    const SortableListItemHandle = SortableHandle(() => (

            <div className="tw-sortable-list-item-handle">
                <svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path fill="currentColor" d="M41 288h238c21.4 0 32.1 25.9 17 41L177 448c-9.4 9.4-24.6 9.4-33.9 0L24 329c-15.1-15.1-4.4-41 17-41zm255-105L177 64c-9.4-9.4-24.6-9.4-33.9 0L24 183c-15.1 15.1-4.4 41 17 41h238c21.4 0 32.1-25.9 17-41z"></path>
                </svg>
            </div>
    ));

    const SortableList = SortableContainer(SortableListComponent, {withRef: true});

    return {
        arrayMove,
        SortableList,
        SortableListComponent
    }
}
