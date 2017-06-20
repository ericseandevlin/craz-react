import * as React from 'react';

import Menu from './Menu.jsx';

import {
  blogMaybeLoadMenu,
} from './../storage/actions/blog.jsx';

export default class Footer extends React.Component {
  /**
   * Load menu by location
   * @param location
   */
  loadMenu(location) {
    this.props.dispatch(blogMaybeLoadMenu(location));
  }

  render() {
    const footerStyle = {
      position: 'fixed',
      width: '100%',
      zIndex: '1',
      bottom: '0'
    };

    return (
      <footer className="footer-section" style={footerStyle}>
        <div className="w-container">
          <div className="w-row">
            <div className="w-col w-col-6 w-col-small-small-stack w-col-tiny-tiny-stack">
              <p className="colophon">©2017 – The Weather Company, LLC. All Rights Reserved.</p>
            </div>
            <Menu location="footer" loadPostHandler={(location) => this.loadMenu(location)}
                  className="legal-column w-col w-col-6 w-col-small-small-stack w-col-tiny-tiny-stack"/>
          </div>
        </div>
        {this.props.children}
      </footer>
    );
  }
}
