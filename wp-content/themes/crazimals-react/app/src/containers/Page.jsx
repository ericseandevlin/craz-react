import React, {Component} from 'react';
import {connect} from 'react-redux';
// import ReactDOM from 'react-dom';

import Post from './../components/Post.jsx';
import Error from './../components/Error.jsx';

import {
  blogMaybeLoadPage,
  blogMaybeLoadAuthors,
  BLOG_LOADING_STATUS_ERROR
} from './../storage/actions/blog.jsx';

class Page extends Component {
  // componentDidUpdate() {
  //   const anchor = location.hash.replace('#', '');
  //
  //   if (anchor && this.refs[anchor]) {
  //     debugger;
  //     const domElement = ReactDOM.findDOMNode(this.refs[anchor]);
  //     if (domElement) {
  //       domElement.scrollIntoView();
  //     }
  //   }
  // }
  componentWillMount() {
    const {dispatch} = this.props;
    // load post data, categories, tags and authors (if not loaded)
    dispatch(blogMaybeLoadPage(this.getSlug(), 'view'));
    dispatch(blogMaybeLoadAuthors());
  }

  // componentWillReceiveProps(nextProps) {
  //   if (this.props.params.slug != nextProps.params.slug) {
  //     // load new post data (when reusing component)
  //     nextProps.dispatch(blogMaybeLoadPage(this.getSlug(nextProps), 'view'));
  //   }
  // }

  /**
   * Get current post slug
   */
  getSlug(props) {
    if (typeof props === 'undefined') {
      props = this.props;
    }
    return props.params.slug.replace(/\/\ /i, '');
  }

  render() {
    const {blog} = this.props;
    const page = blog.page || blog.pages && blog.pages[this.getSlug()] || {};

    // show error if loading  was failed
    if (page.loading && page.loading.status == BLOG_LOADING_STATUS_ERROR) {
      return (<Error code={page.loading.code}/>);
    }

    return (
      <div className="page-container">
        <Post post={page} tags={{}} categories={{}} authors={blog.authors} context="view" pageType="page"/>
      </div>
    );
  }
}

// select only blog data from store
function select(state) {
  return {
    blog: state.blog
  }
}

// connect component with store
export default connect(select)(Page);
