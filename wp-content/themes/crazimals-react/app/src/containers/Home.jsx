import React, {Component} from 'react';
import {connect} from 'react-redux';

import Feed from './../components/Feed.jsx';
import Preview from './Preview.jsx';


import {
  blogMaybeLoadFeed,
  blogMaybeLoadCategories,
  blogMaybeLoadTags,
  blogMaybeLoadAuthors,
  blogMaybeLoadPost
} from './../storage/actions/blog.jsx';

class Home extends Component {

  componentWillMount() {
    const {dispatch} = this.props;
    // load categories, tags and authors (if not loaded)
    dispatch(blogMaybeLoadCategories());
    dispatch(blogMaybeLoadTags());
    dispatch(blogMaybeLoadAuthors());
  }

  /**
   * Load feed by page number
   * @param page
   */
  loadFeedPage(page) {
    this.props.dispatch(blogMaybeLoadFeed('date', 'desc', page));
  }

  /**
   * Load post data by slug
   */
  loadPost(slug) {
    this.props.dispatch(blogMaybeLoadPost(slug, 'feed'));
  }

  /**
   * get current feed key
   */
  getKey() {
    return 'date:desc';
  }

  getHome() {
    const {blog} = this.props;
    const key = this.getKey();
    const feed = blog.feeds[key] ? blog.feeds[key] : {};

    return (
      <div className="home-container">
        <Feed
          key={key}
          pageType="home"
          title="Recent Posts"
          feed={feed}
          posts={blog.posts}
          tags={blog.tags}
          categories={blog.categories}
          authors={blog.authors}
          loadPostHandler={(slug) => this.loadPost(slug)}
          loadFeedPageHandler={(page) => this.loadFeedPage(page)}
        />
      </div>
    );
  }

  parseQuery(qstr) {
    let query = {};
    let a = (qstr[0] === '?' ? qstr.substr(1) : qstr).split('&');
    for (let i = 0; i < a.length; i++) {
      let b = a[i].split('=');
      query[decodeURIComponent(b[0])] = decodeURIComponent(b[1] || '');
    }
    return query;
  }

  getPreview(pageID) {
    const {blog} = this.props;
    return (
      <div className="preview-container">
        <Preview blog={blog} slug={pageID}/>
      </div>
    )
  }

  render() {
    const {location} = this.props; // could be '?foo=bar'
    let params = this.parseQuery(location.search);
    if (location.search && params && params.p) {
      return this.getPreview(+params.p)
    }
    return this.getHome()
  }
}

// select only blog data from store
function select(state) {
  return {
    blog: state.blog
  }
}

// connect component with store
export default connect(select)(Home);