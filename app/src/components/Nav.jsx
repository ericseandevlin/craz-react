import React, {Component} from 'react';

export default class Nav extends React.Component {
  render() {
    const navStyle = {
      // position: 'fixed',
      // width: '100%',
      // zIndex: '1',
      // top: '0'
    };
    const defaultOptions = {
      animation: "default",
      collapse: "none",
      duration: 400,
    };

    return (
      <div>
        <nav className="navbar w-nav" style={navStyle} data-animation="default" data-collapse="none"
             data-duration="400">
          <div className="container-2 w-container">
            <a className="brand w-nav-brand" href="http://crazimals.com">
              <img className="image-3 img" src="/wp-content/themes/crazimals/app/images/craz_logo_white.svg"
                   width="222.5"/>
            </a>
            <div className="w-nav-button">
              <div className="w-icon-nav-menu"></div>
            </div>
          </div>
          {this.props.children}
        </nav>
      </div>
    );
  }
}
