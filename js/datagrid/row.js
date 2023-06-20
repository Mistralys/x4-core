"use strict";

class GridRow
{
    /**
     * @param {String} elementID
     */
    constructor(elementID)
    {
        this.elementID = elementID;
    }

    /**
     * @return {HTMLElement}
     */
    GetElement()
    {
        return document.getElementById(this.elementID);
    }

    /**
     * @return {boolean}
     */
    IsCollapsed()
    {
        return this.IsCollapsible() && this.GetElement().classList.contains('collapsed');
    }

    /**
     * @return {boolean}
     */
    IsCollapsible()
    {
        return this.GetElement().classList.contains('collapsible');
    }

    Toggle()
    {
        if(this.IsCollapsed())
        {
            this.Expand();
        }
        else
        {
            this.Collapse();
        }
    }

    Collapse()
    {
        if(!this.IsCollapsible()) {
            return;
        }

        let el = this.GetElement();
        el.classList.remove('expanded');
        el.classList.add('collapsed');
    }

    Expand()
    {
        if(!this.IsCollapsible()) {
            return;
        }

        let el = this.GetElement();
        el.classList.remove('collapsed');
        el.classList.add('expanded');
    }
}
