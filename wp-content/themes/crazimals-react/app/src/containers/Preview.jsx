import React, {Component} from 'react';
import {connect} from 'react-redux';

import Single from './Single.jsx';
import Post from './../components/Post.jsx';
import Error from './../components/Error.jsx';

import {
  blogMaybeLoadCategories,
  blogMaybeLoadTags,
  blogMaybeLoadAuthors,
  blogMaybeLoadPost,
  BLOG_LOADING_STATUS_ERROR
} from './../storage/actions/blog.jsx';

class Preview extends Component {

  componentWillMount() {
    const {dispatch} = this.props;
    // load post data, categories, tags and authors (if not loaded)
    dispatch(blogMaybeLoadPost(this.getSlug(), 'view'));
    dispatch(blogMaybeLoadCategories());
    dispatch(blogMaybeLoadTags());
    dispatch(blogMaybeLoadAuthors());
  }

  /**
   * Load post data by slug
   */
  loadPost(slug) {
    this.props.dispatch(blogMaybeLoadPost(slug, 'feed'));
  }

  /**
   * Callback, called when Post will shown
   *
   * @param slug
   * @param index
   */
  onPostMounted(slug) {
    console.log("Post mounted");
    const {loadPostHandler} = this.props;

    // load post data
    if (typeof loadPostHandler === 'function') {
      // setTimeout is used for avoiding changing a state during rendering
      setTimeout(() => loadPostHandler(slug), 50);
    }
  }

  /**
   * Get current post slug
   */
  getSlug(props) {
    if (typeof props === 'undefined') {
      props = this.props;
    }
    return props.params && props.params.slug || props.slug;
  }

  render() {
    const {blog, slug} = this.props;
    const post = blog.posts[this.getSlug()] || {};
console.log("post: ", post);

    // show error if loading  was failed
    if (post.loading && post.loading.status == BLOG_LOADING_STATUS_ERROR) {
      return (<Error code={post.loading.code}/>);
    }

    return (
      <div className="posts">
        <Post
          slug={slug}
          post={post}
          loadPostHandler={(slug) => this.loadPost(slug)}
          onMountHandler={() => this.onPostMounted(slug)}
          tags={blog.tags}
          categories={blog.categories}
          authors={blog.authors}
          context="view"
          pageType="preview"
        />
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
export default connect(select)(Preview);
