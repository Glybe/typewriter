/*
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

import {Modal, Notice, Spinner} from "@wordpress/components";
import {compose} from "@wordpress/compose";
import {withSelect} from "@wordpress/data";
import {useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {stripTags} from "../util/sanitize";

export function useSelectPostDialog()
{
    const SelectPostDialog = (props =>
    {
        const title = props.title || "Choose an object...";
        const [searchQuery, setSearchQuery] = useState("");

        return (
                <Modal
                        focusOnMount={true}
                        shouldCloseOnClickOutside={false}
                        shouldCloseOnEsc={true}
                        title={__(title, "tw")}
                        onRequestClose={props.onRequestClose}>

                    <input
                            tabIndex={0}
                            type="search"
                            className="tw-dialog-search"
                            placeholder={__("Search for objects...", "tw")}
                            value={searchQuery}
                            onInput={evt => setSearchQuery(evt.target["value"])}/>

                    <SelectPostDialogResults
                            postType={props.postType || "page"}
                            searchQuery={searchQuery}
                            onSelect={id => props.onSelect(id)}/>
                </Modal>
        );
    });

    const SelectPostDialogResults = compose(
            withSelect((select, props) =>
            {
                const {getEntityRecords} = select("core");

                const results = getEntityRecords("postType", props.postType, {
                    order: "asc",
                    orderby: "title",
                    per_page: 5,
                    search: props.searchQuery
                });

                return {
                    results
                };
            })
    )(({results, onSelect}) =>
    {
        if (results === null) return (
                <div className="tw-dialog-results is-loading">
                    <Spinner/>
                </div>
        );

        if (results.length === 0) return (
                <div className="tw-dialog-results">
                    <Notice status="warning" isDismissible={false}>
                        {__("No objects were found.", "tw")}
                    </Notice>
                </div>
        );

        return (
                <div className="tw-dialog-results">
                    {results.map(result => (
                            <a className="tw-dialog-result" onClick={() => onSelect(result.id)}>
                                {result.title && <strong>{stripTags(result.title.rendered)}</strong>}
                                {result.excerpt && <span>{stripTags(result.excerpt.rendered)}</span>}
                            </a>
                    ))}
                </div>
        );
    });

    return {
        SelectPostDialog,
        SelectPostDialogResults
    };
}
