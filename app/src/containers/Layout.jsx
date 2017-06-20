import React, {Component} from 'react';
import {connect} from 'react-redux';
import Header from '../components/Header.jsx';
import Nav from '../components/Nav.jsx';
import Footer from '../components/Footer.jsx';

import {
  blogMaybeLoadMenus,
} from './../storage/actions/blog.jsx';
class Layout extends Component {
  componentWillMount() {
    const {dispatch} = this.props;
    // load menus (if not loaded)
    dispatch(blogMaybeLoadMenus());
  }

  render() {
    return (
      <div id="layout">
        <Header>
          <Nav animation={"default"} collapse={"none"} duration={400}/>
        </Header>
        {this.props.children}
        <Footer />
      </div>
    );
  }
}

function select(state) {
  return {};
}

export default connect(select)(Layout);
