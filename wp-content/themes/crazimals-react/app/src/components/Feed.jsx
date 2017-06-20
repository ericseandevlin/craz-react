import React, {Component} from 'react';
import {connect} from 'react-redux';
import LazyLoad, {forceCheck} from 'react-lazyload';

import Post from './Post.jsx';
import Error from './Error.jsx';
import SectionsContainer from './../components/FullPage/SectionsContainer.jsx';

import {BLOG_LOADING_STATUS_ERROR, BLOG_LOADING_STATUS_IN_PROGRESS} from './../storage/actions/blog.jsx';

export default class Feed extends Component {

  constructor() {
    super();
    this.state = {
      current: 0
    };
  }

  componentWillMount() {
    // load first page of feed
    if (typeof this.props.loadFeedPageHandler === 'function') {
      this.props.loadFeedPageHandler(1);
    }
  }

  /**
   * Callback, called when Post will shown
   *
   * @param slug
   * @param index
   */
  onPostMounted(slug, index) {
    console.log("Post mounted");
    const {feed, loadPostHandler, loadFeedPageHandler} = this.props;

    // load post data
    if (typeof loadPostHandler === 'function') {
      // setTimeout is used for avoiding changing a state during rendering
      setTimeout(() => loadPostHandler(slug), 50);
    }

    // load next feed page (if needed)
    if (typeof loadFeedPageHandler === 'function' && index == Math.max(feed.postsSlugs.length - 3, 0)) {
      let nextPage = feed.page + 1;
      if (nextPage <= feed.pages && !feed.loading['page' + nextPage]) {
        setTimeout(() => loadFeedPageHandler(nextPage), 50);
      }
    }
  }

  render() {
    const {title, feed, posts, tags, categories, authors, pageType} = this.props;

    // if feed loading was failed
    if (feed.loading && feed.loading['page1'] && feed.loading['page1'].status == BLOG_LOADING_STATUS_ERROR) {
      return (<Error code={feed.loading['page1'].code}/>);
    }

    // map feed posts slugs to Posts
    let postsNodes = null;
    let anchors = [];
    if (feed && feed.postsSlugs) {
      postsNodes = feed.postsSlugs.map((slug, index) => {
        const post = posts[slug] || {};
        anchors.push(slug);
        return (
          //lazy load posts
          <LazyLoad key={slug} height={'100%'} throttle={200} offset={1001} once={true}>
            <Post slug={slug} onMountHandler={() => this.onPostMounted(slug, index)} post={post} tags={tags} categories={categories} authors={authors} context="feed" pageType={pageType}/>
          </LazyLoad>
        );
      });
    }

    // show one post preloader while feed is loading
    if (!postsNodes && feed.loading && feed.loading['page1'] && feed.loading['page1'].status == BLOG_LOADING_STATUS_IN_PROGRESS) {
      postsNodes = (<Post post={{}} context="feed" pageType={pageType}/>);
    }


    let options = {
      className: "sections-container",
      anchors: anchors,
      sectionClassName: "section",
      arrowNavigation: true, // use arrow keys
      // delay: 700, // the scroll animation speed
      navigation: false, // use dots navigation
      scrollBar: false,
      verticalAlign: false,
      sectionPaddingTop: '0',
      sectionPaddingBottom: '50px',
      scrollCallback: (states) => {
        console.log('scrollCallback');
        this.setState({current: states.activeSection || 0});
        forceCheck();
      }
    };

    return (
      <main className={pageType + " main-section posts"}>
        <SectionsContainer className="container" {...options} activeSection={this.state.current}>
          {postsNodes}
        </SectionsContainer>
      </main>
    );
  }
}
