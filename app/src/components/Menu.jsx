import React, {Component} from 'react';
import {connect} from 'react-redux';

import Link from './../components/Html/Link.jsx';
import {BLOG_LOADING_STATUS_ERROR, BLOG_LOADING_STATUS_IN_PROGRESS} from './../storage/actions/blog.jsx';

class Menu extends Component {

  constructor() {
    super();
    this.state = {
      current: ''
    };
  }

  componentWillMount() {
    const {onMountHandler} = this.props;
    if (typeof onMountHandler === 'function') {
      onMountHandler();
    }
  }

  /**
   * Callback, called when Menu will shown
   *
   * @param location
   */
  onMenuMounted(location) {
    console.log("Menu mounted");
    const {loadMenuHandler} = this.props;

    // load post data
    if (typeof loadMenuHandler === 'function') {
      // setTimeout is used for avoiding changing a state during rendering
      setTimeout(() => loadMenuHandler(location), 50);
    }
  }

  getMenu(location) {
    const {blog} = this.props;
    console.log('blog %o', blog);
    const {menuId} = blog.menus.locations[location];
    const {menu} = blog.menus.menus["'" + menuId + "'"];
    return menu;

  }
  getMenuLink(params, content) {
    return (
      <Link {...params}>
        {content}
      </Link>
    )
  }

// <a className="legal-links" href="http://google.com" target="_blank">Terms of Use</a>
// <a className="legal-links" href="http://google.com" target="_blank">Privacy Policy</a>
// <a className="legal-links" href="http://google.com" target="_blank">Ad Choices</a>
  render() {
    const {location, className} = this.props;
    const {menus} = this.props.blog;

    // if menus loading was failed
    if (menus && menus.loading && menus.loading.status == BLOG_LOADING_STATUS_ERROR) {
      return (<Error code={menus.loading.code}/>);
    }

    // map feed posts slugs to Posts
    let menu = null;
    let menuNodes = null;
    if (menus && menus.menus && menus.locations) {
      menu = menus.menus[menus.locations[location]];

      menuNodes = menu.items.map((item, index) => {
        let params = {
          key: item.slug,
          className: item.classes.join(" ") + " legal-links",
          href: item.link,
          // item: item,
          alt: item.attr_title || item.title,
          target: item.target || '_blank'
        };
        return (
          <Link {...params}>{item.title}</Link>
        );
      });
    }
    //       <LazyLoad key={slug} height={'100%'} throttle={200} offset={1001} once={true}>
    //         <Post slug={slug} onMountHandler={() => this.onPostMounted(slug, index)} post={post} tags={tags} categories={categories} authors={authors} context="feed" pageType={pageType}/>
    //       </LazyLoad>


    // show one menu preloader while feed is loading
    if (!menu && menus && menus.loading && menus.loading.status == BLOG_LOADING_STATUS_IN_PROGRESS) {
      menuNodes = (<span/>);
    }

    return (
        <nav className={className + " " + location + "-nav navigation"}>
          {menuNodes}
        </nav>
    );
  }
}


function select(state) {
  return {
    blog: state.blog
  }
}

export default connect(select)(Menu);
