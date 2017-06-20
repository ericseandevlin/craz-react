import React, {Component} from 'react';
import {connect} from 'react-redux';

import Section from './../components/FullPage/Section.jsx';
import Video from './../components/Html/Video.jsx';
import Image from './../components/Html/Image.jsx';
import Link from './../components/Html/Link.jsx';
import Html from './../components/Html.jsx';

class Post extends Component {

  componentWillMount() {
    const {onMountHandler} = this.props;
    if (typeof onMountHandler === 'function') {
      onMountHandler();
    }
  }

  getAuthor() {
    const {post, authors} = this.props;
    if (post.author && authors.users) {
      for (let i in authors.users) {
        if (authors.users[i].id == post.author) {
          return authors.users[i];
        }
      }
    }
    return null;
  }

  getCategories() {
    const {post, categories} = this.props;
    if (post.categories && categories.terms) {
      return categories.terms.filter((term) => {
        return post.categories.indexOf(term.id) !== -1;
      });
    }
    return null;
  }

  getTags() {
    const {post, tags} = this.props;
    if (post.tags && tags.terms) {
      return tags.terms.filter((term) => {
        return post.tags.indexOf(term.id) !== -1;
      });
    }
    return null;
  }

  getTitleHtml() {
    const {context, post} = this.props;
    if (post.title) {
      if (context == 'feed') {
        // render title with link in posts feed
        return (<h1 className="post-title"><Link href={post.link}>{post.title}</Link></h1>);
      }
      return (<h1 className="post-title">{post.title}</h1>);
    }
    // render title preloader
    return (<h1 className="post-title preloader">&nbsp;</h1>);
  }

  getAvatarHtml() {
    const author = this.getAuthor();
    if (!!author) {
      return (<Image className="avatar" width="48" height="48" {...author.avatar} />)
    }
    // render avatar preloader
    return (<div className="avatar preloader"/>);
  }

  getMetaHtml() {
    const author = this.getAuthor();
    const categories = this.getCategories();
    if (!!author || !!categories) {

      let authorLink = !!author ? (<Link className="post-author" href={author.link}>{author.name}</Link>) : null;

      let categoriesLinks = categories.map((term) => {
        let className = "post-category post-category-" + term.slug.replace(/[^0-9a-z\-\_]/i, '');
        return (<span key={term.id}><Link className={className} href={term.link}>{term.name}</Link> </span>);
      });
      let categoriesPrefix = categoriesLinks.length > 0 ? 'under' : '';

      return (<p className="post-meta">By {authorLink} {categoriesPrefix} {categoriesLinks}</p>);
    }
    // render post meta preloader
    return (<p className="post-meta preloader">&nbsp;</p>)
  }

  getThumbnailHtml() {
    //<img sizes="(max-width: 767px) 100vw, (max-width: 991px) 728px, 940px" src="images/baby_otters_16x9.jpg" srcset="images/baby_otters_16x9-p-800.jpeg 800w, images/baby_otters_16x9-p-1080.jpeg 1080w, images/baby_otters_16x9.jpg 1152w">
    const {post} = this.props;

    if (post.thumbnail === null) {
      return null; // there are no thumbnail
    }
    if (!!post.thumbnail) {
      // render thumbnail
      return (<div className="post-images pure-g thumbnail-preloader"><Image {...post.thumbnail} /></div>)
    }

    // render thumbnail preloader
    return (<div className="post-images pure-g thumbnail-preloader">
      <div className="preloader"></div>
    </div>)
  }

  getContentHtml() {
    const {post} = this.props;

    //return (<Html content={post.thecontent}/>);
    return (<Html content={post.content}/>);

    // const {context, post} = this.props;
    //if (context == 'feed') {
    //if (typeof post.summary !== 'undefined') {
    // render post summary with "Read more" link
    //     return [
    //       (<Html key="1" content={post.summary}/>),
    //       (<p key="2"><Link href={post.link}>Read more</Link></p>)
    //     ];
    //   }
    // } else {
    //   if (typeof post.content !== 'undefined') {
    //     // render post content
    //     return (<Html content={post.content}/>);
    //   }
    //   else if (typeof post.summary !== 'undefined') {
    //     // render post summary + preloader (in case content hasn't been loaded yet, but summary exists)
    //     return [
    //       (<Html key="1" content={post.summary}/>),
    //       (<div key="2" className="html preloader"><p><span></span><span></span></p></div>)
    //     ];
    //   }
    // }

    // render content preloader
    return (
      <div className="html preloader"><p><span></span><span></span><span></span></p><p><span></span><span></span></p>
      </div>);
  }

  getFooterMetaHtml() {
    const tags = this.getTags();
    const {post} = this.props;
    if (!tags && post.id) {
      return null; // there are no tags
    }
    if (!!tags) {
      let tagsLinks = tags.map((term) => {
        return (<span key={term.id}><Link href={term.link}>{term.name}</Link> </span>);
      });
      return (<p className="post-meta">Tags: {tagsLinks}</p>)
    }
    // render preloader
    return (<p className="post-meta preloader">&nbsp;</p>)
  }

  getShareButtons() {
    return (
      <div className="erahs-btns">
        <a className="erahs-fbk w-inline-block" href="https://www.facebook.com/crazimals/" target="_blank"></a>
        <a className="erahs-tw w-inline-block" href="https://twitter.com/crazimalz?lang=en" target="_blank"></a>
      </div>
    )
  }

  getVideoTag() {
    const {post} = this.props;
    let options = {
      // video: video,
      post: post
    };
    return (
      <Video {...options} />
    );
  }

  getVideoSection() {
    return (
      <div className={this.getClassName("video-container")}>
        {this.getThumbnailHtml()}
        {this.getVideoTag()}
      </div>
    )
  }

  getClassName(classes) {
    const {post} = this.props;

    return classes + ' ' + post.template;
  }

  getColorMeta() {
    const {post} = this.props;

    if (post.color) {
      return post.color;
    }

    return "#ffffff";
  }

  getPostTemplate() {
    return (
      <div className={this.getClassName("post-text")}>
        <header className="post-header">
          {this.getTitleHtml()}
        </header>
        <div className="post-description">
          {this.getContentHtml()}
        </div>
        <div className="after-post">
          {this.getShareButtons()}
        </div>
      </div>
    )
  }

  getTemplate() {
    const {post} = this.props;

    switch (post.template) {
      case '_1-1':
        return (
          <div className="row w-row size-1-1">
            <div className="video-column w-col w-col-6 w-col-small-6 w-col-stack">
              {this.getVideoSection()}
            </div>
            <div className="w-col w-col-6 w-col-small-6 w-col-stack">
              {this.getPostTemplate()}
            </div>
          </div>
        );
        break;
      case '_2-3':
        return (
          <div className={"row w-row size-2-3"}>
            <div className="video-column w-clearfix w-col w-col-6 w-col-small-6">
              {this.getVideoSection()}
            </div>
            <div className="w-col w-col-6 w-col-small-6">
              {this.getPostTemplate()}
            </div>
          </div>
        );
        break;
      default:
        return (
          <div className={post.template + " size-16-9"}>
            <div className={this.getClassName("post-content w-container")}>
              {this.getVideoSection()}
              {this.getPostTemplate()}
            </div>
          </div>
        );
        break;
    }
  }

  render() {
    const {pageType} = this.props;

    if ("single" == pageType) {
      return (
        <main className="main-section">
          {this.getTemplate()}
        </main>
      );
    } else {
      return (
        <Section className="section-slide" color={this.getColorMeta()}>
          {this.getTemplate()}
        </Section>
      );
    }
  }
}


function select(state) {
  return {
    blog: state.blog
  }
}

export default connect(select)(Post);
