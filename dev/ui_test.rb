require "selenium-webdriver"
require 'test/unit/assertions'

include Test::Unit::Assertions

#options.binary = "/opt/local/bin/chromedriver"

module Selenium::WebDriver::SearchContext 
  def find_by_name(name)
    find_element(xpath: "//*[@data-test='#{name}']")
  end

  def find_by_text(str)
    find_element(xpath: "(//span|div|a|td)[contains(text(),'#{str}')]")
  end
end


class Tester

  def initialize(args)
    base = Pathname.new(__FILE__).parent.parent
    config = JSON.parse(File.read(base.join('Config/Test.json')))

    @super_username = config['super']['username']
    @super_password = config['super']['password']

    @database_binary = config['database']['binary']
    @database_name = config['database']['name']
    @database_user = config['database']['user']
    @database_password = config['database']['password']

    @username = "testuser"
    @password = 'TvoGhZXZqR4bUP'
  end

  def run
    options = Selenium::WebDriver::Chrome::Options.new
    @driver = Selenium::WebDriver.for :chrome
    @wait = Selenium::WebDriver::Wait.new(:timeout => 10) # seconds

    reset_database
    init_database
    create_admin_user
    log_in
    install_tools
    install_templates
    create_front_page

    sleep 60
  end

  def init_database
    @driver.navigate.to "http://www.editor.test/Editor/"

    assert_true(@driver.find_element(:tag_name => "body").text.include?("Databasen er ikke korrekt"))

    find("update-database").click

    find("db-update-username").send_keys "super"
    find("db-update-password").send_keys "duper"
    find("db-update-submit").click

    logWindow = find("database-log-window")
    @wait.until { logWindow.displayed? }

    logWindow.find_element(css: '.hui_window_close').click
  end

  def create_admin_user
    # The message may obscure something
    @wait.until { not @driver.find_element(css: ".hui_message").displayed? }

    @driver.find_element(link_text: "Glemt kodeord?").click

    @driver.find_element(link_text: "Ny bruger").click

    find("new-user-super-username").send_keys @super_username
    find("new-user-super-password").send_keys @super_password
    find("new-user-username").send_keys @username
    find("new-user-password").send_keys @password
    find("new-user-submit").click
    find("new-user-super-username").send_keys "super"
    @wait.until { not find("new-user").displayed? }
    puts "Admin user created"
  end

  def log_in
    @driver.navigate.to "http://www.editor.test/Editor/"

    @driver.find_by_name("username").send_keys @username

    @driver.find_by_name("password").send_keys @password

    @driver.find_by_name('login').click
  end

  def install_tools
    @wait.until { @driver.find_element(link_text: "System") }
    @driver.find_element(link_text: "System").click
    @driver.switch_to.frame @driver.find_element(:class, 'hui_dock_frame')
    @driver.find_by_text("Tools").click
    ['Sites','Images','Files'].each do |key|
      @wait.until { @driver.find_element(xpath:"//tr[td[text()='#{key}']]/td/a") }
      sleep 1
      @driver.find_element(xpath:"//tr[td[text()='#{key}']]/td/a").click
    end
    @driver.navigate.to "http://www.editor.test/Editor/"
  end

  def install_templates
    @driver.find_element(link_text: "Setup").click
    @driver.find_element(link_text: "System").click
    @driver.switch_to.frame @driver.find_element(:class, 'hui_dock_frame')
    @driver.find_by_text("Templates").click
    ['document'].each do |key|
      @wait.until { @driver.find_element(xpath:"//tr[td[text()='#{key}']]/td/a") }
      sleep 1
      @driver.find_element(xpath:"//tr[td[text()='#{key}']]/td/a").click
    end
  end

  def create_front_page
    @driver.navigate.to "http://www.editor.test/Editor/"
    @wait.until { @driver.find_element(link_text: "Pages") }
    @driver.find_element(link_text: "Pages").click
    @driver.switch_to.frame @driver.find_element(:class, 'hui_dock_frame')
    @wait.until { @driver.find_element(link_text: "New page") }
    @driver.find_element(link_text: "New page").click
  end

  def reset_database
    system "#{@database_binary} -u #{@database_user} -p#{@database_password} -e \"drop database #{@database_name};\""

    system "#{@database_binary} -u #{@database_user} -p#{@database_password} -e \"create database #{@database_name};\""
  end

  def find(name)
    @driver.find_by_name(name)
  end
end

#driver.quit

if __FILE__ == $0
  x = Tester.new(ARGV)
  x.run # or go, or whatever
end